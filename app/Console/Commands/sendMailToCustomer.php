<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Cookie;
use Session;
use Redirect;
use Input;
use Validator;
use DB;
use IsAdmin;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Admin;
use App\Models\Setting;
use Mail;
use App\Mail\SendMailable;

class sendMailToCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:sendmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending mail for next appointment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $todaydate=date('Y-m-d');
        $appoinmentrecords=Appointment::whereDate('next_appointment_date','=',$todaydate)->get();

        foreach ($appoinmentrecords as $appoinmentdata) {
            $userdata=[];
            $userdata['email_address']='';
            $userdata['first_name']='';
            $staffslug='';
            if($appoinmentdata->staff_id!=1){
                if($appoinmentdata->Admin){
                    $staffslug=$appoinmentdata->Admin->slug;
                }
            }

            if($appoinmentdata->user_id!=null && $appoinmentdata->user_id!=0){
                if($appoinmentdata->User){
                    $userdata=$appoinmentdata->User;
                }
            }else{
                $userdata['email_address']=$appoinmentdata->guest_email;
                $userdata['first_name']=$appoinmentdata->guest_name;
            }
            if($userdata['first_name']==null || $userdata['first_name']==''){
                $userdata['first_name']='Customer';
            }
            if($userdata['email_address']!=null || $userdata['email_address']!=''){
                $link = HTTP_PATH . "/selectservice/" . $staffslug;
                $name = ucwords($userdata['first_name']);
                $emailId = $userdata['email_address'];
                $emailTemplate = DB::table('emailtemplates')->where('id', 11)->first();
                $toRepArray = array('[!username!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

            }
        }
    }
}
