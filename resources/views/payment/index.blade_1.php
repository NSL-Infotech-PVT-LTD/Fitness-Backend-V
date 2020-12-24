<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Payment
                </div>
                <?php
// Test API key taken from documentation
//                define('NMI_KEY', '2F822Rw39fx762MaV7Yy86jXGTC7sCDy');
                define('NMI_KEY', 'NGY4YzE1YzUtYTJhNS00OTZiLThkNmItZjU3NGM0MmRjZTAxOmY2MWExODUyLWRlZDAtNDQwZi1iZDZlLThhMmE5YmQ0NmRmZA==');
               
                $nmi = new App\Helpers\Nmi3Step(NMI_KEY,url('pay'));
// $nmi->set_debug(true); // uncomment this to expose dumps of sent and received XML data

                set_exception_handler(function(Exception $e) {
                    echo '<h3>An exception occurred</h3>';
                    echo '<p><em>' . $e->getMessage() . '</em></p>';
                });

                if (!empty($_GET['token-id'])) {
                    $payment = $nmi->submit_payment($_GET['token-id']);

                    echo '<h3>Transaction approved</h3>';
                    echo '<p>The code for this transaction is "<strong>' . $payment->{'transaction-id'} . '</strong>"</p>';
                    echo '<p><a href="' . $_SERVER['PHP_SELF'] . '">Go again</a></p>';
                } else {
                    $amount = 5;
                    $form_url = $nmi->get_url($amount);

                    $html = '<form action="' . $form_url . '" method="post">
		<fieldset class="align">
			<div><label>Num</label> <input type="text" name="billing-cc-number" placeholder="Num" value="5431111111111111"></div>
			<div><label>Exp</label> <input type="text" name="billing-cc-exp" placeholder="Exp" class="short" value="10/10"></div>
			<div><label>CVV</label> <input type="text" name="billing-cvv" placeholder="CVV" class="short" value="111"></div>
			<input type="submit" value="Submit to NMI" class="submit">
		</fieldset>
	</form>';

                    echo $html;
                }
                ?>

            </div>
        </div>
    </body>
</html>
