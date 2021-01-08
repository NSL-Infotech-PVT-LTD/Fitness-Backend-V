<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }}</title>
   </head>
   <body >
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width: 55%;
         margin: auto;
         border: 1px solid #d0d0d0;
         padding: 20px;">
         <!-- START OF TOP BAR-->
         <tr>
            <td>
               <div class="logo_top" style="text-align:center;padding:20px 0; background:#000">
                  <img src="{{ asset('image/logo1.png') }}" alt="logo" style="width:100px;"> 
               </div>
            </td>
         </tr>
         <tr>
            <td>
               <img src="{{ asset('image/banner.png') }}" alt="banner" style="width:100%;"> 
            </td>
         </tr>
         <tr>
            <td>
               <table cellpadding="0" cellspacing="0" style="">
                  <tr>
                     <td>
                        <h2 style="padding: 20px 0px 0px 0px; color:#000;font-weight:900;line-height:20px;font-size:15px;">Hi Akshay </h2>
                        <p style="padding: 0px; color:#000;font-size:15px;font-weight:400;line-height:20px;padding:0px 0px 20px 0px;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled i  </p>
                     </td>
                     <!-- START OF SUBJECT LINE-->
                  </tr>
               </table>
               </div>
            </td>
         </tr>
         <tr style="background: #ffc439;text-align: center;">
            <td>
               <table cellspacing="0" cellpadding="0" style="margin: 20px auto;">
                  <tr>
                     <h2 style="padding: 20px 0px 0px 0px; color:#000;font-weight:900;line-height:20px;font-size:20px;margin: 0;">Payment </h2>
                     <td style="border-radius:70px;">
                        <a  href="{{$payment_href}}" target="_blank" style=" text-decoration: none;
                           padding: 18px 40px;
                           display: block;
                           color: #fff;
                           background: #000;
                           font-weight: bold;
                           font-size: 15px;border-radius: 70px;"> Click Here to Pay Now     
                        </a>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <td>
               <table border="0" cellpadding="0" cellspacing="0" class="scale" data-thumb="#" width="100%" mc:variant="Header5314" mc:repeatable="Header5314">
                  <tbody>
                     <tr>
                        <td>
                           <table border="0" cellpadding="0" cellspacing="0" class="scale" style="background-color:#3598DA;width:100%;">
                              <tbody>
                                 <tr>
                                    <td>
                                       <table align="left" border="0" cellpadding="0" cellspacing="0" class="scale-300" width="330">
                                          <tbody>
                                             <tr>
                                                <td height="80" style="font-size: 1px" mc:edit="space">&nbsp;</td>
                                             </tr>
                                             <tr>
                                                <td class="scale-center-both">
                                                   <table align="right" border="0" cellpadding="0" cellspacing="0" class="scale" width="300">
                                                      <tbody>
                                                         <tr>
                                                            <td class="scale" style="color: #F5F5F5;">
                                                               <br>
                                                               <a href="#" target="_blank"><img src="{{ asset('image/header_btn.png') }}" mc:edit="app store"></a> 
                                                               <br>   <br>   <br>
                                                               <a href="#" target="_blank"><img src="{{ asset('image/header_btn2.png') }}" mc:edit="app icon"></a>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <table align="right" border="0" cellpadding="0" cellspacing="0" class="scale-300" width="282">
                                          <tbody>
                                             <tr>
                                                <td class="scale-right" valign="bottom" mc:edit="main image"><img src="image/header.png" style="display: block" class="scale-inline" mc:edit="main image"></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <!-- Profile-->
         <tr>
            <td>
               <p style=" font-size:13px;padding:15px 0px 10px 0px;font-weight:500;line-height:20px;">Regards,<br>
                  <b>Akshay</b>
               </p>
            </td>
         </tr>
         <tr>
            <td>
               <div style="padding:10px 0px;">
                  <img src="{{ asset('image/logo_footer.png') }}" alt="logo" style="width:100px;"></a>
               </div>
         </tr>
      </table>
   </body>
</html>