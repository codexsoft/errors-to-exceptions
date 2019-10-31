<?php

namespace CodexSoft\ErrorsToExceptions;

/**
 * inspired by https://www.php.net/manual/ru/function.set-error-handler.php#112881
 *
 * The error handlers are stacked with set_error_handler(), and popped with restore_error_handler()
 * https://www.php.net/manual/ru/function.restore-error-handler.php#82139
 */
class ErrorsToExceptions
{

    public static function enable()
    {
        /**
         * throw exceptions based on E_* error types
         */
        \set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) {
                return false;
            }

            if (!(error_reporting() & $err_severity)) {
                // Этот код ошибки не включен в error_reporting,
                // так что пусть обрабатываются стандартным обработчиком ошибок PHP
                return false;
            }

            switch($err_severity)
            {
                case E_ERROR:               throw new \ErrorException           ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_WARNING:             throw new WarningException          ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_PARSE:               throw new ParseException            ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_NOTICE:              throw new NoticeException           ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_CORE_ERROR:          throw new CoreErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_CORE_WARNING:        throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_COMPILE_ERROR:       throw new CompileErrorException     ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_COMPILE_WARNING:     throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_ERROR:          throw new UserErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_WARNING:        throw new UserWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_NOTICE:         throw new UserNoticeException       ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_STRICT:              throw new StrictException           ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_RECOVERABLE_ERROR:   throw new RecoverableErrorException ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_DEPRECATED:          throw new DeprecatedException       ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_DEPRECATED:     throw new UserDeprecatedException   ($err_msg, 0, $err_severity, $err_file, $err_line);
                default:
                    return true;
            }
        });
    }

    /**
     * Disables errors-to-exceptions convertation
     */
    public static function disable(): void
    {
        \restore_error_handler();
    }

}
