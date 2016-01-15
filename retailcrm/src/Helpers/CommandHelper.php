<?php

class CommandHelper
{
    public static function runHelp()
    {
        echo "\n\033[35;2;18mUsage:\033[0m\n";
        echo "  /usr/bin/php [-c /etc/php5/cli/php.ini] -f app.php -e command [-l] [-u] [-p ids] [-r reference] [-h history] [-m mail@example.com]\n";
        echo "\n\033[35;2;18mCommands:\033[0m\n";
        echo "  icml\t\tGenerate icml export file\n";
        echo "  history\tGet data from crm\n";
        echo "  orders\tExport orders to crm\n";
        echo "  customers\tExport customers to crm\n";
        echo "  references\tExport references to crm\n";
        echo "  mail\t\tExport orders from mailbox\n";
        echo "  amo\t\tExport customers & orders from amoCRM\n";
        echo "  dump\t\tCreate mysql dump\n";
        echo "\n\033[35;2;18mArguments:\033[0m\n";
        echo "  -l\t\tExport orders or customers created from last run\n";
        echo "  -u\t\tExport orders or customers updated from last run\n";
        echo "  -c\t\tCustom export for orders or customers\n";
        echo "  -m\t\tMail address (for mail command)\n";
        echo "  -p\t\tPass set of ids or single id for export customer or order by this ids\n";
        echo "  -r\t\tExport references, if type is set only this reference will be exported\n";
        echo "  -h\t\tHistory type, if type is set only this history will be recieved\n";
    }

    public static function dumpNotice()
    {
        echo "\033[0;31mUnfortunately for the database can not be used to make the dump\033[0m\n";
    }

    public static function notWorkGetOptNotice()
    {
        echo "\033[0;31mDoes not function getopt. It is used to obtain the parameters from the command line. Please refer to the server administrator.\033[0m\n";
    }

    public static function updateNotice()
    {
        echo "\033[0;31mFull update is not allowed, please select one of the following flags: limit, set of identifiers or a specific id\033[0m\n";
    }

    public static function implementationNotice($name, $type)
    {
        echo "\033[0;36mThis function is not implemented. You need to create $name$type\033[0m\n";
    }

    public static function implementationError($name, $iface)
    {
        echo "\033[0;31m$name class must implement $iface\033[0m\n";
    }

    public static function settingsNotice()
    {
        echo "\033[0;31msettings.ini doesn't exist\033[0m\n";
    }

    public static function activateNotice($param)
    {
        echo "\033[0;31mActivate \"$param\" section in settings.ini or check your connection settings\033[0m\n";
    }

    public static function paramNotice($param)
    {
        echo "\033[0;31mParameter \"$param\" is mandatory\033[0m\n";
    }

    public static function settingsFailure($param)
    {
        echo "\033[0;31mKey \"$param\" doesn't not exist in settings.ini\033[0m\n";
    }

    public static function refHelp($ref)
    {
        switch($ref) {
            case 'references':
                echo "\033[0;36mAvailable values: delivery-types, delivery-services, payment-types, payment-statuses, statuses\033[0m\n";
                break;
            case 'history':
                echo "\033[0;36mAvailable values: orders, customers\033[0m\n";
                break;
        }

    }

}
