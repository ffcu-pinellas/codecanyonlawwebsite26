<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSectionInputs extends Model
{
    use HasFactory;

    // home page section inputs
    public function slider($page)
    {
        $input = '<input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="slider">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($page->sections()->where('name','slider')->first()?($page->sections()->where('name','slider')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($page->sections()->where('name','slider')->first()?($page->sections()->where('name','slider')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="total-attorney" class="card-title">'.__('Number of image shown?').'</label>
                                        <input type="number" name="number_of_content" id="total-attorney" class="form-control"  value="'.($page->sections()->where('name','slider')->first()?clean($page->sections()->where('name','slider')->first()->number_of_content):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($page->sections()->where('name','slider')->first()?($page->sections()->where('name','slider')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_slider'
        ];;
    }

    public function about($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="about">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <p class="mb-1 text-left">About section feature image: <code>This image will show home about section\'s left side</code></p>
                                    <div class="home-page-about-img" id="about_left_img">
                                        <div class="input-images"></div>
                                    </div>
                                    <img src="'.($settingsPage->sections()->where('name','about')->first()?$settingsPage->sections()->where('name','about')->first()->fnt_img:'').'" alt="" class="img-thumbnail w-25 mt-5 border">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','about')->first()?clean($settingsPage->sections()->where('name','about')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','about')->first()?clean($settingsPage->sections()->where('name','about')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="10" >'.($settingsPage->sections()->where('name','about')->first()?clean($settingsPage->sections()->where('name','about')->first()->description):'').'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'about_left_img'
        ];
    }

    public function service($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="service">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','service')->first()?($settingsPage->sections()->where('name','service')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','service')->first()?($settingsPage->sections()->where('name','service')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','service')->first()?clean($settingsPage->sections()->where('name','service')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','service')->first()?clean($settingsPage->sections()->where('name','service')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','service')->first()?clean($settingsPage->sections()->where('name','service')->first()->description):'').'</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="total-attorney" class="card-title">'.__('Number of services shown?').'</label>
                                        <input type="number" name="number_of_content" id="total-attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','service')->first()?clean($settingsPage->sections()->where('name','service')->first()->number_of_content):'').'">
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <img src="'.($settingsPage->sections()->where('name','service')->first()?$settingsPage->sections()->where('name','service')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                </div>
                                <div class="col-md-6">
                                    <p class="card-title">'.__('Background Image').'</p>
                                    <div class="home-page-service-img" id="breadcumb_bg_img">
                                        <div class="input-images"></div>
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','service')->first()?($settingsPage->sections()->where('name','service')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_service'
        ];
    }

    public function testimonial($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="testimonial">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','testimonial')->first()?($settingsPage->sections()->where('name','testimonial')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','testimonial')->first()?($settingsPage->sections()->where('name','testimonial')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','testimonial')->first()?clean($settingsPage->sections()->where('name','testimonial')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">{'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','testimonial')->first()?clean($settingsPage->sections()->where('name','testimonial')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','testimonial')->first()?clean($settingsPage->sections()->where('name','testimonial')->first()->description):'').'</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="total-attorney" class="card-title">'.__('Number of testimonials shown?').'</label>
                                        <input type="number" name="number_of_content" id="total-attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','testimonial')->first()?clean($settingsPage->sections()->where('name','testimonial')->first()->number_of_content):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','testimonial')->first()?($settingsPage->sections()->where('name','testimonial')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_testimonial'
        ];
    }

    public function appointment($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="appointment">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','appointment')->first()?($settingsPage->sections()->where('name','appointment')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','appointment')->first()?($settingsPage->sections()->where('name','appointment')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-form-title" class="card-title">'.__('Form Title').'</label>
                                        <input type="text" name="form_title" id="contact-form-title" class="form-control"  value="'.($settingsPage->sections()->where('name','appointment')->first()?clean($settingsPage->sections()->where('name','appointment')->first()->form_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="form-subtitle" class="card-title">'.__('Form Sub-title').'</label>
                                        <input type="text" name="form_subtitle" id="form-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','appointment')->first()?clean($settingsPage->sections()->where('name','appointment')->first()->form_subtitle):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','appointment')->first()?clean($settingsPage->sections()->where('name','appointment')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','appointment')->first()?clean($settingsPage->sections()->where('name','appointment')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','appointment')->first()?clean($settingsPage->sections()->where('name','appointment')->first()->description):'').'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','appointment')->first()?($settingsPage->sections()->where('name','appointment')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_appointment'
        ];
    }

    public function case_study($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="case_study">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','case_study')->first()?($settingsPage->sections()->where('name','case_study')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','case_study')->first()?($settingsPage->sections()->where('name','case_study')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','case_study')->first()?clean($settingsPage->sections()->where('name','case_study')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','case_study')->first()?clean($settingsPage->sections()->where('name','case_study')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','case_study')->first()?clean($settingsPage->sections()->where('name','case_study')->first()->description):'').'</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="number-case-study" class="card-title">'.__('Number of case studies shown?').'</label>
                                        <input type="number" name="number_of_content" id="total-attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','case_study')->first()?clean($settingsPage->sections()->where('name','case_study')->first()->number_of_content):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','appointment')->first()?($settingsPage->sections()->where('name','appointment')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_case_study'
        ];
    }

    public function attorney($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="attorney">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','attorney')->first()?($settingsPage->sections()->where('name','attorney')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','attorney')->first()?($settingsPage->sections()->where('name','attorney')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','attorney')->first()?clean($settingsPage->sections()->where('name','attorney')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','attorney')->first()?clean($settingsPage->sections()->where('name','attorney')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','attorney')->first()?clean($settingsPage->sections()->where('name','attorney')->first()->description):'').'</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="number-case-study" class="card-title">'.__('Number of attorney shown?').'</label>
                                        <input type="number" name="number_of_content" id="total-attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','attorney')->first()?clean($settingsPage->sections()->where('name','attorney')->first()->number_of_content):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','attorney')->first()?($settingsPage->sections()->where('name','attorney')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_attorney'
        ];
    }

    public function blog($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="blog">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','blog')->first()?($settingsPage->sections()->where('name','blog')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','blog')->first()?($settingsPage->sections()->where('name','blog')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','blog')->first()?clean($settingsPage->sections()->where('name','blog')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','blog')->first()?clean($settingsPage->sections()->where('name','blog')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','blog')->first()?clean($settingsPage->sections()->where('name','blog')->first()->description):'').'</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="number-case-study" class="card-title">'.__('Number of blogs shown?').'</label>
                                        <input type="number" name="number_of_content" id="total-attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','blog')->first()?clean($settingsPage->sections()->where('name','blog')->first()->number_of_content):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','blog')->first()?($settingsPage->sections()->where('name','blog')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_blog'
        ];
    }

    public function partner($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="home">
                        <input type="hidden" name="group" value="partner">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','partner')->first()?($settingsPage->sections()->where('name','partner')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','partner')->first()?($settingsPage->sections()->where('name','partner')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','partner')->first()?clean($settingsPage->sections()->where('name','partner')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                        <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','partner')->first()?clean($settingsPage->sections()->where('name','partner')->first()->sub_title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                        <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','partner')->first()?clean($settingsPage->sections()->where('name','partner')->first()->description):'').'</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','partner')->first()?($settingsPage->sections()->where('name','partner')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'home_page_partner'
        ];
    }

    // contact page section inputs
    public function contact_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="contact">
                        <input type="hidden" name="group" value="contact_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <p class="card-title">'.__('Breadcamb Background Image').' &nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                            <div class="contact-page-breadcumb-bg-img" id="breadcumb_bg_img">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','contact_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','contact_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>
                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','contact_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','contact_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'contact_breadcumb_bg_img'
        ];
    }

    public function contact_info($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="contact">
                        <input type="hidden" name="group" value="contact_info">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','contact_info')->first()?($settingsPage->sections()->where('name','contact_info')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','contact_info')->first()?($settingsPage->sections()->where('name','contact_info')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact-title" class="card-title">'.__('Title').'</label>
                                        <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','contact_info')->first()?clean($settingsPage->sections()->where('name','contact_info')->first()->title):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact-line-one" class="card-title">'.__('Line One').'</label>
                                        <input type="text" name="line_one" id="contact-line-one" class="form-control"  value="'.($settingsPage->sections()->where('name','contact_info')->first()?clean($settingsPage->sections()->where('name','contact_info')->first()->line_one):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact-line-two" class="card-title">'.__('Line Two').'</label>
                                        <input type="text" name="line_two" id="contact-line-two" class="form-control" value="'.($settingsPage->sections()->where('name','contact_info')->first()?clean($settingsPage->sections()->where('name','contact_info')->first()->line_two):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','contact_info')->first()?($settingsPage->sections()->where('name','contact_info')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'contact_page_contact_info'
        ];
    }

    public function business_hour($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="contact">
                        <input type="hidden" name="group" value="business_hour">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','business_hour')->first()?($settingsPage->sections()->where('name','business_hour')->first()->show?'checked':''):'').'>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','business_hour')->first()?($settingsPage->sections()->where('name','business_hour')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="hour-line-one" class="card-title">'.__('Line One').'</label>
                                        <input type="text" name="line_one" id="hour-line-one" class="form-control"  value="'.($settingsPage->sections()->where('name','business_hour')->first()?clean($settingsPage->sections()->where('name','business_hour')->first()->line_one):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="hour-line-two" class="card-title">'.__('Line Two').'</label>
                                        <input type="text" name="line_two" id="hour-line-two" class="form-control" value="'.($settingsPage->sections()->where('name','business_hour')->first()?clean($settingsPage->sections()->where('name','business_hour')->first()->line_two):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','business_hour')->first()?($settingsPage->sections()->where('name','business_hour')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'contact_page_business_hour'
        ];
    }

    public function email($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="contact">
                        <input type="hidden" name="group" value="email">';
        $cardBody = '<div class="card-body text-black">
                            <div class="row">
                                <div class="col-3 mx-auto">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-right font-weight-bold">'.__('Show').'</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="content-show" '.($settingsPage->sections()->where('name','email')->first()?($settingsPage->sections()->where('name','email')->first()->show?'checked':''):'').'  name="show">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row '.($settingsPage->sections()->where('name','email')->first()?($settingsPage->sections()->where('name','email')->first()->show?'':'d-none'):'d-none').'">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email-one" class="card-title">'.__('Email One').'</label>
                                        <input type="email" name="line_one" id="email-one" class="form-control"  value="'.($settingsPage->sections()->where('name','email')->first()?clean($settingsPage->sections()->where('name','email')->first()->line_one):'').'">
                                    </div>
                                    <div class="form-group">
                                        <label for="email-two" class="card-title">'.__('Email Two').'</label>
                                        <input type="email" name="line_two" id="email-two" class="form-control" value="'.($settingsPage->sections()->where('name','email')->first()?clean($settingsPage->sections()->where('name','email')->first()->line_two):'').'">
                                    </div>
                                </div>
                            </div>
                        </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','email')->first()?($settingsPage->sections()->where('name','email')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'contact_page_email'
        ];
    }

    // about page section inputs
    public function about_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="about">
                        <input type="hidden" name="group" value="about_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <p class="card-title">'.__('Breadcamb Background Image').' &nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                            <div class="about-page-breadcumb-bg-img" id="breadcumb_bg_img">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','about_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','about_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>
                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','about_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','about_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'about_breadcumb_bg_img'
        ];
    }

    public function left_about_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="about">
                        <input type="hidden" name="group" value="left_about_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','left_about_img')->first()?$settingsPage->sections()->where('name','left_about_img')->first()->fnt_img:'').'" alt="" class="img-thumbnail w-50">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="left-about-img" id="left_about_img">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'left_about_img'
        ];
    }

    public function right_about($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="about">
                        <input type="hidden" name="group" value="right_about">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','right_about')->first()?clean($settingsPage->sections()->where('name','right_about')->first()->title):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                                <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','right_about')->first()?clean($settingsPage->sections()->where('name','right_about')->first()->sub_title):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                                <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','right_about')->first()?clean($settingsPage->sections()->where('name','right_about')->first()->description):'').'</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'about_page_right_about'
        ];
    }

    public function about_appointment($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="about">
                        <input type="hidden" name="group" value="about_appointment">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">
                                        <div class="col-3 mx-auto">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td class="text-right font-weight-bold">'.__('Show').'</td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" class="content-show" name="show" '.($settingsPage->sections()->where('name','about_appointment')->first()?($settingsPage->sections()->where('name','about_appointment')->first()->show?'checked':''):'').'>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row '.($settingsPage->sections()->where('name','about_appointment')->first()?($settingsPage->sections()->where('name','about_appointment')->first()->show?'':'d-none'):'d-none').'">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="case-won" class="card-title">'.__('case won').'</label>
                                                <input type="text" name="case_won" id="case-won" class="form-control"  value="'.($settingsPage->sections()->where('name','about_appointment')->first()?clean($settingsPage->sections()->where('name','about_appointment')->first()->case_won):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="total-attorney" class="card-title">'.__('Total Attorney').'</label>
                                                <input type="text" name="total_attorney" id="total-attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','about_appointment')->first()?clean($settingsPage->sections()->where('name','about_appointment')->first()->total_attorney):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="total-client" class="card-title">'.__('total-client').'</label>
                                                <input type="text" name="total_client" id="total-client" class="form-control" value="'.($settingsPage->sections()->where('name','about_appointment')->first()?clean($settingsPage->sections()->where('name','about_appointment')->first()->total_client):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="total-case-dismissed" class="card-title">'.__('total case dismissed').'</label>
                                                <input type="text" name="total_case_dismissed" id="total-case-dismissed" class="form-control" value="'.($settingsPage->sections()->where('name','about_appointment')->first()?clean($settingsPage->sections()->where('name','about_appointment')->first()->total_case_dismissed):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="appointment-form-title" class="card-title">'.__('Appointment Form Title').'</label>
                                                <input type="text" name="form_title" id="appointment-form-title" class="form-control" value="'.($settingsPage->sections()->where('name','about_appointment')->first()?clean($settingsPage->sections()->where('name','about_appointment')->first()->form_title):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="appointment-form-subtitle" class="card-title">'.__('Appointment Form Subtitle').'</label>
                                                <input type="text" name="form_subtitle" id="appointment-form-subtitle" class="form-control" value="'.($settingsPage->sections()->where('name','about_appointment')->first()?clean($settingsPage->sections()->where('name','about_appointment')->first()->form_subtitle):'').'">
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','about_appointment')->first()?($settingsPage->sections()->where('name','about_appointment')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'about__page_about_appointment'
        ];
    }

    public function about_attorney($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="page" value="about">
                        <input type="hidden" name="group" value="about_attorney">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">
                                        <div class="col-3 mx-auto">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td class="text-right font-weight-bold">'.__('Show').'</td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" class="content-show" '.($settingsPage->sections()->where('name','about_attorney')->first()?($settingsPage->sections()->where('name','about_attorney')->first()->show?'checked':''):'').'  name="show">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row '.($settingsPage->sections()->where('name','about_attorney')->first()?($settingsPage->sections()->where('name','about_attorney')->first()->show?'':'d-none'):'d-none').'">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','about_attorney')->first()?clean($settingsPage->sections()->where('name','about_attorney')->first()->title):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="right-about-subtitle" class="card-title">'.__('Sub-title').'</label>
                                                <input type="text" name="sub_title" id="right-about-subtitle" class="form-control"  value="'.($settingsPage->sections()->where('name','about_attorney')->first()?clean($settingsPage->sections()->where('name','about_attorney')->first()->sub_title):'').'">
                                            </div>
                                            <div class="form-group">
                                                <label for="right-about-description" class="card-title">'.__('Description').'</label>
                                                <textarea name="description" id="right-about-description" class="form-control" rows="5" >'.($settingsPage->sections()->where('name','about_attorney')->first()?clean($settingsPage->sections()->where('name','about_attorney')->first()->description):'').'</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="number_of_attorney" class="card-title">'.__('Number Of Attorney will be shown').'</label>
                                                <input type="number" name="number_of_content" id="number_of_attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','about_attorney')->first()?clean($settingsPage->sections()->where('name','about_attorney')->first()->number_of_content):'').'">
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer '.($settingsPage->sections()->where('name','about_attorney')->first()?($settingsPage->sections()->where('name','about_attorney')->first()->show?'':'d-none'):'d-none').'">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'about_page_about_attorney'
        ];
    }

    // services page section inputs
    public function services_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="services">
                        <input type="hidden" name="group" value="services_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <p class="card-title">'.__('Breadcamb Background Image').' &nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                            <div class="breadcumb-bg-img" id="breadcumb_bg_img">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','services_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','services_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>
                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','services_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','services_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for="number_of_attorney" class="card-title">'.__('Number Of Services will be shown in a page').'</label>
                                                <input type="number" name="number_of_content" id="number_of_attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','services_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','services_breadcumb_bg_img')->first()->number_of_content):'').'">
                                            </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'services_breadcumb_bg_img'
        ];
    }

    // cases page section inputs
    public function cases_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="cases">
                        <input type="hidden" name="group" value="cases_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <p class="card-title">'.__('Breadcamb Background Image').' &nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                            <div class="breadcumb-bg-img" id="breadcumb_bg_img">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','cases_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','cases_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>
                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','cases_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','cases_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for="number_of_attorney" class="card-title">'.__('Number Of Cases will be shown in a page').'</label>
                                                <input type="number" name="number_of_content" id="number_of_attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','cases_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','cases_breadcumb_bg_img')->first()->number_of_content):'').'">
                                            </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'cases_breadcumb_bg_img'
        ];
    }

    // blogs page section inputs
    public function blogs_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="blogs">
                        <input type="hidden" name="group" value="blogs_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <p class="card-title">'.__('Breadcamb Background Image').'&nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                            <div class="breadcumb-bg-img" id="breadcumb_bg_img">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','blogs_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','blogs_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>
                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','blogs_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','blogs_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for="number_of_attorney" class="card-title">'.__('Number Of Blogs will be shown in a page').'</label>
                                                <input type="number" name="number_of_content" id="number_of_attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','blogs_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','blogs_breadcumb_bg_img')->first()->number_of_content):'').'">
                                            </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'blogs_breadcumb_bg_img'
        ];
    }

    // teams page section inputs
    public function teams_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="teams">
                        <input type="hidden" name="group" value="teams_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <p class="card-title">'.__('Breadcamb Background Image').' &nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                            <div class="breadcumb-bg-img" id="breadcumb_bg_img">
                                                <div class="input-images"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','teams_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','teams_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>
                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','teams_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','teams_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for="number_of_attorney" class="card-title">'.__('Number Of Members will be shown in a page').'</label>
                                                <input type="number" name="number_of_content" id="number_of_attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','teams_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','teams_breadcumb_bg_img')->first()->number_of_content):'').'">
                                            </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'teams_breadcumb_bg_img'
        ];
    }

    // faq page section inputs
    public function faq_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="faq">
                        <input type="hidden" name="group" value="faq_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">
                                        <div class="col-md-6">
                                                <p class="card-title">'.__('Breadcamb Background Image').' &nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                                <div class="breadcumb-bg-img" id="breadcumb_bg_img">
                                                    <div class="input-images"></div>
                                                </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','faq_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','faq_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>

                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','faq_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','faq_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for="number_of_attorney" class="card-title">'.__('Number Of FAQ will be shown in a page').'</label>
                                                <input type="number" name="number_of_content" id="number_of_attorney" class="form-control"  value="'.($settingsPage->sections()->where('name','faq_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','faq_breadcumb_bg_img')->first()->number_of_content):'').'">
                                            </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'faq_breadcumb_bg_img'
        ];
    }

    // client dashboard section inputs
    public function client_dashboard_breadcumb_bg_img($page)
    {
        $settingsPage = $page;
        $input = '<input type="hidden" name="_token" value="'.@csrf_token().'">
                    <input type="hidden" name="page" value="client_dashboard">
                        <input type="hidden" name="group" value="client_dashboard_breadcumb_bg_img">';
        $cardBody = '<div class="card-body text-black">
                                    <div class="row">
                                        <div class="col-md-6">
                                                <p class="card-title">'.__('Breadcamb Background Image').' &nbsp;<code>Acceptable image size 1920 x 1200 pixel</code></p>
                                                <div class="breadcumb-bg-img" id="breadcumb_bg_img">
                                                    <div class="input-images"></div>
                                                </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="'.($settingsPage->sections()->where('name','client_dashboard_breadcumb_bg_img')->first()?$settingsPage->sections()->where('name','client_dashboard_breadcumb_bg_img')->first()->bg_img:'').'" alt="" class="img-thumbnail w-50 mt-4">
                                        </div>

                                        <div class="col-md-12 form-group">
                                                <label for="contact-title" class="card-title">'.__('Title').'</label>
                                                <input type="text" name="title" id="contact-title" class="form-control"  value="'.($settingsPage->sections()->where('name','client_dashboard_breadcumb_bg_img')->first()?clean($settingsPage->sections()->where('name','client_dashboard_breadcumb_bg_img')->first()->title):'').'">
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        $cardFooter = '<div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-lg rounded">'.__('Save').'</button>
                        </div>';
        $output = $input.$cardBody.$cardFooter;
        return (object)[
            'html' => $output,
            'section' => 'client_dashboard_breadcumb_bg_img'
        ];
    }
}
