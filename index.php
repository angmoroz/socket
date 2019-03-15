<?php
error_reporting(E_ALL);

echo "<h2>Соединение TCP/IP</h2>\n";

/* Получаем порт сервиса WWW. */
$service_port = getservbyname('http', 'tcp');

/* Получаем IP-адрес целевого хоста. */
//$address = gethostbyname('www.example.com');
$address = gethostbyname('localhost/socket/server.php');

/* Создаём сокет TCP/IP. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
	$error=socket_strerror(socket_last_error($result));
	$charset=mb_detect_encoding($error,'IBM866,WINDOWS-1251'); // для возможных кодировок см http://php.net/manual/ru/mbstring.supported-encodings.php
	$error=iconv($charset, 'UTF-8',$error);
    echo "Не удалось выполнить socket_create(): причина: " . $error. "\n";
} else {
    echo "OK.\n";
}

echo "Пытаемся соединиться с '$address' на порту '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
	$error=socket_strerror(socket_last_error($result));
	$charset=mb_detect_encoding($error,'IBM866,WINDOWS-1251'); // для возможных кодировок см http://php.net/manual/ru/mbstring.supported-encodings.php
	$error=iconv($charset, 'UTF-8',$error);
    echo "Не удалось выполнить socket_connect().\nПричина: ($result) " . $error . "\n";
} else {
    echo "OK.\n";
}

$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: www.example.com\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

echo "Отправляем HTTP-запрос HEAD...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Читаем ответ:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Закрываем сокет...";
socket_close($socket);
echo "OK.\n\n";
?>