<?php

namespace App\Helpers;

use App\Models\TgMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class Bot
{
    public $operators;
    public $commands;

    public function __construct()
    {
        $this->operators =  [
            '314136897'
        ];
        $this->commands = [
            '/start',
            '/help',
        ];
        $menus = DB::table('categories')
            ->select('text')
            ->get();
        foreach ($menus as $menu) {
            $this->commands[] =  $menu->text;
        }
    }
    public function reply_markup ($params) {
        $keyboard = [];
        if ($params['isset_tg_user'] || $params['answer_text'] == 'start_0') {

            $parent = DB::table('categories')
                ->select('*')
                ->where('text', '=', $params['message']->text)
                ->get();

            if ($parent[0]->parent_id != 0) {
                $back_menu = DB::table('categories')
                    ->select('*')
                    ->where('id', '=', $parent[0]->parent_id)
                    ->get();
                array_push($keyboard, ['text' => $back_menu[0]->name, 'callback_data' => $back_menu[0]->text]);
            }

            $categories = DB::table('categories')
                ->where('parent_id', '=', $parent[0]->id)
                ->get();


            foreach ($categories as $id => $categoty) {
                array_push($keyboard, ['text' => $categoty->name, 'callback_data' => $categoty->text]);
            }
        }
        return ['inline_keyboard' => [$keyboard]];
    }

    public function answer_name ($params)
    {
        if ($params['isset_tg_user']) {
            $params['answer_text'] = "{$params['func_name']}_text";
            if ($params['func_name'] == 'start' ) { $params['answer_text'] = 'start_1';}
        } else {
            $params['answer_text'] = 'some_text';
            if ($params['func_name'] == 'start' ) {
                $params['answer_text'] = 'start_0';
                //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => '---- start 0!!!' ]);
                $pass = '';
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $alphaLength = strlen($alphabet) - 1;
                for ($i = 0; $i < 8; $i++) {
                    $n = rand(0, $alphaLength);
                    $pass .= $alphabet[$n];
                }

                $params['user_password_nohash'] = $pass;
                $params['user']['password'] = Hash::make($pass);
                $params['user']['email'] = "test{$params['message']->from}@test.ru";
                $params['user']['name'] = "{$params['message']->first_name}_{$params['message']->last_name}_{$params['message']->username}";
                $params['user']['updated_at'] = $params['current_date'];
                $params['user']['created_at'] = $params['current_date'];
                //Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [ 'chat_id' => 314136897, 'text' => $params['user'] ]);
                $user_id = DB::table('users')->insertGetId($params['user']);

                //Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [ 'chat_id' => 314136897, 'text' => ' user id = '.$user_id ]);

                $answ_tg_chats = DB::table('tg_chats')->insert([
                    'chat_id' => "{$params['message']->from}",
                    'user_id' => $user_id,
                    'created_at' => $params['current_date'],
                    'updated_at' => $params['current_date'],
                ]);
                //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $answ_tg_chats ]);
            }
        }
        return $params;
    }
    //------------------------------------------------------------------------------------
    public function make_answer($params) {
        $params = $this::answer_name($params);
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => "---- make answer_name {$params['func_name']}!!" ]);
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $params ]);
        $params['answer_message_text'] = $this->{$params['answer_text']}($params);
        $params['reply_markup'] = $this::reply_markup($params);
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => "---- return result {$params['func_name']}!!" ]);
        return $params;
    }
    public function start_0 ($params)
    {
        $text = " 
Привет! Не нашел тебя в базе. Создал нового пользователя. 
    Данные можно изменить на сайте m-engine.ru 
    Логин: {$params['user']['name']} 
    Почта: {$params['user']['email']} 
    Пароль: {$params['user_password_nohash']} 
    ";
        Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $text ]);
        return $text;
    }
    public function start_1 ($params)
    {
        $text = "
У вас уже есть аккаунт. 
    Логин: {$params['account']['tg_chat']->name}
    Почта: {$params['account']['tg_chat']->email}
                     ";
        return $text;
    }
    public function some_text ($params) {
        $text = " 
Привет! Не нашел тебя в базе. извини, за неудобства. Пожалуйста, напиши команду /start.
    ";
        return $text;
    }
    public function menu_text ($params) {
        $text = " 
Это главное меню. Это сообщение можно вызвать текстовой командой в сообщении /menu.
    ";
        return $text;
    }
    public function artists_text ($params) {
        $text = " 
Это меню исполнителей. Это сообщение можно вызвать текстовой командой в сообщении /artists.
    ";
        return $text;
    }
    public function artists_party_text ($params) {
        $text = " 
Это меню исполнителей. Это сообщение можно вызвать текстовой командой в сообщении /artists_party.
    ";
        return $text;
    }
    public function artists_manager_text ($params) {
        $text = " 
Это меню исполнителей. Это сообщение можно вызвать текстовой командой в сообщении /artists_manager.
    ";
        return $text;
    }
    public function artists_owner_text ($params) {
        $text = " 
Это меню исполнителей. Это сообщение можно вызвать текстовой командой в сообщении /artists_owner.
    ";
        return $text;
    }
    public function account_text ($params) {
        $text = " 
Это Информация об аккаунте. Это сообщение можно вызвать текстовой командой в сообщении /account.
    Id на сайте: {$params['account']['tg_chat']->user_id}
    Логин: {$params['account']['tg_chat']->name}
    Почта: {$params['account']['tg_chat']->email}
    ";
        return $text;
    }
}
