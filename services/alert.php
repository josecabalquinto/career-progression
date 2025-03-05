<?php
class AlertService
{
    public function set__($type, $msg)
    {
        $_SESSION['alert'] = true;
        $_SESSION['message'] = $msg;
        $_SESSION['icon'] = $type;
        switch ($type) {
            case 'success':
                $_SESSION['title'] = "Successful";
                break;

            case 'error':
                $_SESSION['title'] = "Failed";
                break;

            default:
                $_SESSION['title'] = "Warning";
                break;
        }
        return true;
    }
}
