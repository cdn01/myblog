<?php
require_once('class.phpmailer.php');
require_once("class.smtp.php"); 
function tmail($message){
	$mail  = new PHPMailer();  
	$mail->CharSet    ="UTF-8";                 //�趨�ʼ����룬Ĭ��ISO-8859-1����������Ĵ����������Ϊ UTF-8
	$mail->IsSMTP();                            // �趨ʹ��SMTP����
	$mail->SMTPAuth   = true;                   // ���� SMTP ��֤����
	$mail->SMTPSecure = "ssl";                  // SMTP ��ȫЭ��
	$mail->Host       = "smtp.126.com";       // SMTP ������
	$mail->Port       = 465;                    // SMTP�������Ķ˿ں�
	$mail->Username   = "cdn_01@126.com";  // SMTP�������û���
	$mail->Password   = "qingyu";        // SMTP����������
	$mail->SetFrom('cdn_01@126.com', 'cdn_01');    // ���÷����˵�ַ������
	$mail->AddReplyTo("cdn_02@126.com","cdn_02"); 
												// �����ʼ��ظ��˵�ַ������
	$mail->Subject    = $message."����ʧ��";                     // �����ʼ�����
	$mail->AltBody    = "Ϊ�˲鿴���ʼ������л���֧�� HTML ���ʼ��ͻ���"; 
												// ��ѡ����¼��ݿ���
	$mail->MsgHTML($message);                         // �����ʼ�����
	$mail->AddAddress('cdn_02@126.com', "cdn_02");
	//$mail->AddAttachment("images/phpmailer.gif"); // ���� 
	if(!$mail->Send()) {
		echo "����ʧ�ܣ�" . $mail->ErrorInfo;
	} else {
		echo "��ϲ���ʼ����ͳɹ���";
	}
}

?>