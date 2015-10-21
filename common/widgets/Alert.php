<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Alert::add('This is the error message'. Alert::TYPE_ERROR);
 * Alert::add('This is the error message'. Alert::TYPE_DANGER);
 * Alert::add('This is the info message'. Alert::TYPE_INFO);
 * Alert::add('This is the warning message'. Alert::TYPE_WARNING);
 * Alert::add('This is the success message', Alert::TYPE_SUCCESS);
 * Alert::add('This is the success message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Alert::add(['Error 1 msg', 'Error 2 msg'], Alert::TYPE_ERROR);
 * ```
 *
 * or as follows:
 *
 * ```php
 * Alert::add($activeRecordModel->getErrors(), Alert::TYPE_ERROR);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Tomasz Kane <tomasz.kane@gmail.com>
 */
class Alert extends \yii\bootstrap\Widget
{
    const
        TYPE_DANGER = 'danger',
        TYPE_ERROR = 'danger',
        TYPE_INFO = 'info',
        TYPE_SUCCESS = 'success',
        TYPE_WARNING = 'warning';

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    public function init()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        if (!empty($flashes))
        {
            $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';
            $reflectionClass = new \ReflectionClass ('common\widgets\Alert');
            $alertTypes = $reflectionClass->getConstants();

            foreach ($flashes as $type => $data) {
                if (in_array($type, $alertTypes)) {
                    $data = (array)$data;
                    foreach ($data as $i => $message) {
                        /* initialize css class for each alert box */
                        $this->options['class'] = 'alert-' . $type . $appendCss;

                        /* assign unique id to each alert box */
                        $this->options['id'] = $this->getId() . '-' . $type . '-' . $i;

                        echo \yii\bootstrap\Alert::widget(
                            [
                                'body' => is_array($message) ? $this->flatten($message) : $message,
                                'closeButton' => $this->closeButton,
                                'options' => $this->options,
                            ]
                        );
                    }
                    $session->removeFlash($type);
                }
            }
        }
    }

    /**
     * @param string $message
     * @param string $category
     */
    public static function add($message, $category = self::TYPE_SUCCESS)
    {
        $session = Yii::$app->getSession();
        $counters = $session->get($session->flashParam, []);
        $counters[$category] = -1;
        $_SESSION[$category][] = $message;
        $_SESSION[$session->flashParam] = $counters;
    }

    /**
     * @param array $array
     * @return string
     */
    private function flatten(array $array)
    {
        $return = '';
        array_walk_recursive(
            $array,
            function ($a) use (&$return) {
                $return .= $a . '<br>';
            }
        );
        return $return;
    }

}