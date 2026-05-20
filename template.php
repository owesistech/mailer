<?php
$date = date("Y");

$message = '
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expression of Interest Confirmation</title>
    <style>
        body {
            font-family: \'Georgia\', serif;
            line-height: 1.6;
            color: #1a1a1a;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
        }
        .header {
            background-color: #000000;
            color: #ffffff;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .content {
            padding: 40px;
        }
        .content p {
            margin-bottom: 20px;
        }
        .status-box {
            background-color: #f9f9f9;
            border-left: 4px solid #000000;
            padding: 20px;
            margin: 30px 0;
        }
        .footer {
            padding: 30px 40px;
            border-top: 1px solid #eeeeee;
            font-size: 12px;
            color: #777777;
            text-align: center;
        }
        .tagline {
            font-style: italic;
            color: #444444;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>'.$subject.'</h1>
        </div>
        <div class="content">
            <p>Dear <b>'.$name.'</b>,</p>

            <p>Thank you for declaring your interest in the 4th edition of the <strong>200 CEOs Business Forum</strong>.</p>

            <p>As Tanzania’s foremost closed-door convocation of executive leadership, the Forum exists at the intersection of capital, policy, and long-term vision. This is a sovereign gathering—deliberate in design and rigorous in composition.</p>

            <div class="status-box">
                <strong>Next Steps:</strong>
                <ul style="padding-left: 20px; margin-top: 10px;">
                    <li><strong>Review:</strong> Our secretariat is vetting all submissions to ensure a peer-level environment for senior decision-makers.</li>
                    <li><strong>Notification:</strong> A formal update regarding your attendance will be sent within 5 business days.</li>
                </ul>
            </div>

            <p>We appreciate your interest in this year\'s deliberation.</p>

            <p>Respectfully,<br>
            <strong>The Secretariat</strong><br>
            200 CEOs Business Forum</p>
        </div>
        <div class="footer">
            <p class="tagline">At the intersection of capital, policy, and vision.</p>
            <p>&copy; '.$date.' 200 CEOs Business Forum | Dar es Salaam, Tanzania</p>
        </div>
    </div>
</body>
</html>';

?>