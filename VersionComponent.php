<?php
/**
 * Version component for Yii framework
 * You might want to show your current product version
 * Component takes and returns current version
 *
 * Date: 20.03.12 18:07
 * @author Andrew Nestorenko <andrew.nestorneko@gmail.com>
 */

class VersionComponent extends CApplicationComponent
{
    /**
     * @var bool
     */
    public $enable = false;

    /**
     * @var string
     */
    public $prefix = 'v';

    /**
     * @var string
     */
    public $vcs;

    /**
     * @var array
     */
    public $allowedIp;

    /**
     * @param array $allowedIp
     */
    public function setAllowedIp($allowedIp)
    {
        $this->allowedIp = $allowedIp;
    }

    /**
     * @return array
     */
    public function getAllowedIp()
    {
        return (array) $this->allowedIp;
    }

    /**
     * Initialize component
     * @return void
     */
    public function init()
    {

    }

    /**
     * @param $vcs
     * @return void
     */
    public function setVcs($vcs)
    {
        $this->vcs = $vcs;
    }

    /**
     * @return string
     */
    public function getVcs()
    {
        return $this->vcs;
    }

    /**
     * @param $enabled
     * @return void
     */
    public function setEnable($enabled)
    {
        $this->enable = $enabled;
    }

    /**
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param $prefix
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }


    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        if ($this->checkIsEnabled() && false === empty($this->vcs)) {
            switch (strtolower($this->vcs)) {
                case 'git' :
                    $v = $this->getGitVersion();
                    break;
                default   :
                case 'hg' :
                case 'mercurial' :
                    $v = $this->getHgVersion();
                    break;
            }
        } else {
            return '';
        }

        if ($this->isAllowedIp(Yii::app()->request->userHostAddress)) {
            return !empty($v) ? $this->prefix . $v : '';
        }
        return '';
    }


    /**
     * @param $ip
     * @return bool
     */
    public function isAllowedIp($ip)
    {
        $allowedIp = $this->getAllowedIp();
        if(empty($allowedIp)) {
            return true;
        }

        foreach ($this->getAllowedIp() as $filter) {
            if ($filter === '*'
                || $filter === $ip
                || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))
            ) {
                return true;
            }
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function checkIsEnabled()
    {
        return $this->getEnable();
    }

    /**
     * @return string
     */
    private function getGitVersion()
    {
        return `git describe --tag`;
    }

    /**
     * @return string
     */
    protected function getHgVersion()
    {
        $output = `hg log -r . --template '{latesttag}'`;
        if (null == $output) {
            try {
                $webroot = Yii::getPathOfAlias('webroot');
                $fileObj = new SplFileObject($webroot . '/.hgtags');
                while ( ! $fileObj->eof()) {
                    $line = $fileObj->fgets();
                }
                $lineComponents = explode(' ', $line);
                $output = $lineComponents[1];
            } catch (Exception $e) {
                Yii::log('Version component has thrown an exception: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'components');
            }
        }
        return $output == 'null' ? '' : $output;
    }
}