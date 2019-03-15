<?php
//error_reporting(E_ALL);

/* Позволяет скрипту ожидать соединения бесконечно. */
set_time_limit(0);

/* Включает скрытое очищение вывода так, что мы видим данные
 * как только они появляются. */
ob_implicit_flush();

//$address = '192.168.1.53';
$address = '127.0.0.1';
$port = 10456;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
	$error=socket_strerror(socket_last_error($sock));
	$charset=mb_detect_encoding($error,'IBM866,WINDOWS-1251'); // для возможных кодировок см http://php.net/manual/ru/mbstring.supported-encodings.php
	$error=iconv($charset, 'UTF-8',$error);
    echo "Не удалось выполнить socket_create(): причина: " . $error . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
	$error=socket_strerror(socket_last_error($sock));
	$charset=mb_detect_encoding($error,'IBM866,WINDOWS-1251'); // для возможных кодировок см http://php.net/manual/ru/mbstring.supported-encodings.php
	$error=iconv($charset, 'UTF-8',$error);
   // echo "Не удалось выполнить socket_bind(): причина: " . iconv('WINDOWS-1251', 'UTF-8',socket_strerror(socket_last_error($sock))) . "\n";
    echo "Не удалось выполнить socket_bind(): причина: " . $error . "\n";
}

if (socket_listen($sock, 5) === false) {
	$error=socket_strerror(socket_last_error($sock));
	$charset=mb_detect_encoding($error,'IBM866,WINDOWS-1251'); // для возможных кодировок см http://php.net/manual/ru/mbstring.supported-encodings.php
	$error=iconv($charset, 'UTF-8',$error);
   // echo "Не удалось выполнить socket_listen(): причина: " . iconv('WINDOWS-1251', 'UTF-8',socket_strerror(socket_last_error($sock))) . "\n";
    echo "Не удалось выполнить socket_listen(): причина: " . $error . "\n";
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
	$error=socket_strerror(socket_last_error($sock));
	$charset=mb_detect_encoding($error,'IBM866,WINDOWS-1251'); // для возможных кодировок см http://php.net/manual/ru/mbstring.supported-encodings.php
	$error=iconv($charset, 'UTF-8',$error);
       // echo "Не удалось выполнить socket_accept(): причина: " . iconv('WINDOWS-1251', 'UTF-8',socket_strerror(socket_last_error($sock))) . "\n";
        echo "Не удалось выполнить socket_accept(): причина: " . $error . "\n";
        break;
    }
    /* Отправляем инструкции. */
    $msg = "\nДобро пожаловать на тестовый сервер PHP. \n" .
        "Чтобы отключиться, наберите 'выход'. Чтобы выключить сервер, наберите 'выключение'.\n";
    socket_write($msgsock, $msg, strlen($msg));

    do {
        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
			$error=socket_strerror(socket_last_error($sock));
			$charset=mb_detect_encoding($error,'IBM866,WINDOWS-1251'); // для возможных кодировок см http://php.net/manual/ru/mbstring.supported-encodings.php
			$error=iconv($charset, 'UTF-8',$error);
            echo "Не удалось выполнить socket_read(): причина: " . $error  . "\n";
            break 2;
        }
        if (!$buf = trim($buf)) {
            continue;
        }
        if ($buf == 'выход') {
            break;
        }
        if ($buf == 'выключение') {
            socket_close($msgsock);
            break 2;
        }
        $talkback = "PHP: Вы сказали '$buf'.\n";
        socket_write($msgsock, $talkback, strlen($talkback));
        echo "$buf\n";
    } while (true);
    socket_close($msgsock);
} while (true);

socket_close($sock);
?>