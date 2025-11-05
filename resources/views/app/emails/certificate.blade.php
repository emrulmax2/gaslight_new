<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <title>Welcome to GasEngineerApp.co.uk</title>
      <style>
         body { margin:0; padding:0; background-color:#f5f7fa; font-size: 16px; font-family:'Segoe UI', Arial, sans-serif; color:#333333; }
         table { border-collapse:collapse; width:100%; }
         a { text-decoration:none; color:#0b4b6f; }
         .container { max-width:700px; margin:40px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08); }
         .header { background-color:#0b4b6f; color:#ffffff; padding:30px 20px; text-align:center; }
         .header h1 { margin:0; font-size:24px; letter-spacing:0.3px; }
         .body { padding:40px; line-height:1.6; }
         .body h2 { color:#0b4b6f; font-size:20px; margin-bottom:16px; }
         .body p { margin-bottom:16px; font-size:15px; }
         .cta { display:inline-block; background-color:#0b4b6f; color:#ffffff !important; padding:12px 24px; border-radius:4px; font-weight:600; margin-top:10px; }
         .footer { background-color:#f0f3f6; padding:25px 40px; font-size:13px; color:#555555; }
         .footer a { color:#0b4b6f; text-decoration:none; }
         .footer .contact { margin-bottom:0; }
         .footer .company { font-weight:500; }
         @media only screen and (max-width:600px) {
         .body, .footer, .header { padding:20px; }
         .header h1 { font-size:20px; }
         }
      </style>
   </head>
   <body>
      <div class="container">
         <!-- Header -->
         <div class="header">
            <table style="border-collapse: collapse; border: 0;">
               <tr>
                  <td style="width: 30%; padding: 0; text-align: left;">
                     <img src="https://app.gascertificate.app/logos/logo_1_new.png" alt="Logo" style="height:60px; width: auto;">
                  </td>
                  <td style="text-align: right; width: 60%;">
                     <h1 style="color: #FFF; text-align:right; font-size: 18px; line-height: 22px; word-break: break-word;">{{ $title }}</h1>
                  </td>
               </tr>
            </table>
         </div>
         <!-- Body -->
         <div class="body">
			{!! $content !!}
         </div>
         <!-- Footer -->
         <div class="footer">
            <div class="contact">
               <span class="company">Powered by Engineer App Ltd</span><br>
            </div>
         </div>
      </div>
   </body>
</html>