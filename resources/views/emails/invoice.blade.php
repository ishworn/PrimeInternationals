<!DOCTYPE html>
<html>
<head>
    <title>Invoice Email</title>
</head>
<body>
    <p>Dear {{ $sender->senderName }},</p> <!-- Use the sender's name or any other attribute from the sender model -->
    <p>We hope this email finds you well. Please find attached your invoice.</p>
    <p><strong>Invoice Details:</strong></p>
   
    <p>Thank you for choosing Prime Gurkha Logistics. If you have any questions regarding your invoice, feel free to reach out.</p>
    <p>Best regards,<br> Prime Gurkha Logistics Team</p>
</body>
</html>
