<?php

namespace Workdo\LockerAndSafeDeposit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Workdo\LandingPage\Entities\MarketplacePageSetting;


class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $module = 'LockerAndSafeDeposit';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Locker & Safe Deposit';
        $data['product_main_description'] = '<p>This Add-On provides an end-to-end solution for managing locker and safe deposit operations. It allows seamless customer registration, locker allocation based on size and type, and flexible booking with manual renewal tracking. The system supports dynamic pricing, membership plans, and secure payment tracking with receipt generation.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Locker & Safe Deposit Integration in Dash SaaS';
        $data['dedicated_theme_description'] = '<p>Locker & Safe Deposit keeps your important documents and assets protected within Workdo Dash.</p>';
        $data['dedicated_theme_sections'] = '[
        {"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Customer Management","dedicated_theme_section_description":"<p>Easily manage customers with their contact details, ID proof, and address records. Track customer history, including bookings, renewals, and payments, ensuring efficient locker allocation and smooth operations.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},

        {"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Locker Allocation & Pricing","dedicated_theme_section_description":"<p>Easily assign lockers to customers based on size, type, and availability. Control pricing for both monthly and yearly rental plans with an automated pricing system.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},

        {"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Booking & Renewals","dedicated_theme_section_description":"<p>The system provides a seamless process for customers to book lockers based on their preferred size and rental duration and renew their locker rentals before expiry. Each booking is tracked with essential details such as the start date, rental period, and payment amount. Lockers have different pricing models for monthly or yearly rentals.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},

        {"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Locker Maintenance & Repairs","dedicated_theme_section_description":"<p>To ensure lockers remain in optimal condition, the system logs maintenance requests, technician details, and repair status. Each repair is documented with descriptions and timestamps, helping administrators track locker conditions efficiently.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},

        {"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Locker Membership Plans","dedicated_theme_section_description":"<p>For customers who prefer long-term locker usage, a membership system allows them to subscribe based on predefined durations and membership types. Each membership is linked to a customer and locker, with fees recorded accordingly.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"LockerAndSafeDeposit"},{"screenshots":"","screenshots_heading":"LockerAndSafeDeposit"},{"screenshots":"","screenshots_heading":"LockerAndSafeDeposit"},{"screenshots":"","screenshots_heading":"LockerAndSafeDeposit"},{"screenshots":"","screenshots_heading":"LockerAndSafeDeposit"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => $module

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
