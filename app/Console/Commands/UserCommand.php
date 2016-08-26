<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\PartnerUser;

class UserCommand extends Command {

    protected $status;

    protected $lineLength = 48;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'my:user';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create new user";
    /**
     * Execute the console command.
     *
     * @return void
     */

    function __construct()
    {
        parent::__construct();
        $this->status = ['Inactive' => 0, 'Active' => 1, 'Deactivate' => 2];
    }

    public function fire()
    {
        $action = $this->input->getArgument('action');

        switch ($action) {
            case 'create':
                $attrs = ['Username', 'Email', 'Password', 'Status'];

                $this->line(str_pad("", $this->lineLength, "-"));
                $this->line(str_pad(strtoupper(" input new detail user "), $this->lineLength, '*', STR_PAD_BOTH));
                $this->line(str_pad("", $this->lineLength, "-"));

                $username = str_random(32);

                $email = $this->ask('Email');

                $status = $this->choice(
                    'Set status', 
                    ['Inactive', 'Active', 'Deactivate']
                );

                $password = str_random(8);
                $hashedPassword = app('hash')->make($password);

                $header = ['Attribute', 'Value'];
                $rows = [];

                foreach ($attrs as $k => $attr) {
                    $rows[$k]['Attribute'] = $attr;

                    if ($k==0)
                        $rows[$k]['Value'] = $username;

                    
                    if ($k==1)
                        $rows[$k]['Value'] = $email;

                    
                    if ($k==2)
                        $rows[$k]['Value'] = $password;

                    
                    if ($k==3)
                        $rows[$k]['Value'] = $status;

                }

                $this->table($header, $rows);

                $confirm = $this->confirm('Are you sure to save this user?', false);

                if ($confirm) {
                    $user = new PartnerUser;
                    $user->username = $username;
                    $user->email    = $email;
                    $user->password = $hashedPassword;
                    $user->activated = $this->status[$status];
                    if ($user->save()) {
                        $this->info('Success to create new user!');
                    }
                }

                break;
            
            default:
                # code...
                break;
        }
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('action', InputArgument::OPTIONAL, 'The action you want to do', 'create')
        );
    }

}