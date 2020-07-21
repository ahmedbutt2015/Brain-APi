<?php

namespace App\Http\Controllers;

use App\MessageStatus;
use App\Notifications\TwilioNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Message;


class MessageController extends Controller
{
    const WEBHOOK_PASSWORD_KEY = 'webhook';

    public function index(Request $request)
    {
        $unread = $request->input('unread');
        $direction = $request->input('direction');

        $messages = Message::where('user_id', '=', auth()->user()->id)->orderBy('id', 'desc');

        if($unread){
            $messages->whereNull('read_at');
        }

        if($direction){
            $messages->where('direction', '=', $direction);
        }

        $messages = $messages->paginate(15);

        $messagesCollection = $messages->map(function($message) use ($unread) {
            if($unread){
                $message->read_at = Carbon::now()->format('Y-m-d H:i:s');
                $message->save();
            }
            $message->response = json_decode($message->response);

            return $message;
        });


        $messages->setCollection($messagesCollection);

        return $messages;
    }

    public function show(Message $message)
    {
        $message->response = json_decode($message->response);
        return $message;
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['from'] = $request->user()->twilio_phone_number;
        $data['direction'] = 'outbound';
        $data['user_id'] = auth()->user()->id;
        $message = Message::create($data);

        $request->user()->notify(new TwilioNotification($message));

        $message['response'] = json_decode($message['response']);
        unset($message['user_id']);
        return response()->json($message, 201);
    }

    public function update(Request $request, Message $message)
    {
        $message->update($request->all());

        return response()->json($message, 200);
    }

    public function delete(Message $message)
    {
        $message->delete();

        return response()->json(null, 204);
    }

    public function webhookStatus(Request $request)
    {
        $data = $request->all();
        $userPass = explode(':', base64_decode($data[self::WEBHOOK_PASSWORD_KEY]));

        if($userPass[0] == '4195a7f0-1250-445d-95e4-430fa7d93551' &&
            $userPass[1] == '396b9a1c-19f4-11ea-978f-2e728ce88125')
        {
            unset($data[self::WEBHOOK_PASSWORD_KEY]);

            $status = [];
            $status['message_id'] = Message::getByTwilioSid($data['SmsSid'])->id;
            $status['status'] = $data['SmsStatus'];
            $status['response'] = json_encode($data);
            $messageStatus = MessageStatus::create($status);
            unset($messageStatus['message_id']);
            $messageStatus['response'] = $data;

            return response()->json($messageStatus, 201);
        }

        return response()->json(["error" => "Unauthenticated"], 401);
    }

    public function webhookMessage(Request $request)
    {
        $data = $request->all();
        $userPass = explode(':', base64_decode($data[self::WEBHOOK_PASSWORD_KEY]));

        if($userPass[0] == '4195a7f0-1250-445d-95e4-430fa7d93551' &&
            $userPass[1] == '396b9a1c-19f4-11ea-978f-2e728ce88125')
        {
            unset($data[self::WEBHOOK_PASSWORD_KEY]);
            $data['Body'] = urldecode($data['Body']);
            $data['From'] = urldecode($data['From']);

            $messageData = [];
            $messageData['from'] = Message::extractNumber(urldecode($data['From']));
            $messageData['to'] = Message::extractNumber(urldecode($data['To']));
            $messageData['channel'] = Message::extractChannel(urldecode($data['From']));
            $messageData['direction'] = 'inbound';
            $messageData['user_id'] = User::where('twilio_phone_number', '=', $messageData['to'])->firstOrFail()->id;
            $messageData['body'] = urldecode($data['Body']);
            $messageData['response'] = json_encode($data);
            $message = Message::create($messageData);

            $message['response'] = json_decode($message['response']);
            unset($message['user_id']);

            return response()->json($message, 201);
        }

        return response()->json(["error" => "Unauthenticated"], 401);
    }

    public function messageStatus(Request $request, Message $message)
    {
        $unread = $request->input('unread');

        $statuses = MessageStatus::where('message_id', '=', $message->id)->orderBy('id', 'desc');

        if($unread){
            $statuses->whereNull('read_at');
        }

        $statuses = $statuses->paginate(15);

        $statusesCollection = $statuses->map(function($status) use ($unread) {
            if($unread){
                $status->read_at = Carbon::now()->format('Y-m-d H:i:s');
                $status->save();
            }
            $status->response = json_decode($status->response);

            return $status;
        });


        $statuses->setCollection($statusesCollection);

        return $statuses;
    }

    public function status(Request $request)
    {
        $unread = $request->input('unread');

        $request->user()->twilio_phone_number;

        $statuses = MessageStatus::getList()->orderBy('message_status.id', 'desc');

        if($unread){
            $statuses->whereNull('message_status.read_at');
        }

        $statuses = $statuses->paginate(15);

        $statusesCollection = $statuses->map(function($status) use ($unread) {
            if($unread){
                $status->read_at = Carbon::now()->format('Y-m-d H:i:s');
                $status->save();
            }
            $status->response = json_decode($status->response);

            return $status;
        });


        $statuses->setCollection($statusesCollection);

        return $statuses;
    }
}
