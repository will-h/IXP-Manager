<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatchPanelPortObject
 */
class PatchPanelPortObject
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Entities\ConsoleServerConnection
     */
    private $ConsoleServerConnection;

    /**
     * @var \Entities\CustomerEquipment
     */
    private $CustomerEquipment;

    /**
     * @var \Entities\PatchPanelPort
     */
    private $PatchPanelPort;

    /**
     * @var \Entities\SwitchPort
     */
    private $SwitchPort;

    /**
     * @var \Entities\PatchPanelPort
     */
    private $PatchPort;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ConsoleServerConnection
     *
     * @param \Entities\ConsoleServerConnection $consoleServerConnection
     * @return PatchPanelPortObject
     */
    public function setConsoleServerConnection(\Entities\ConsoleServerConnection $consoleServerConnection)
    {
        $this->ConsoleServerConnection = $consoleServerConnection;
    
        return $this;
    }

    /**
     * Get ConsoleServerConnection
     *
     * @return \Entities\ConsoleServerConnection 
     */
    public function getConsoleServerConnection()
    {
        return $this->ConsoleServerConnection;
    }

    /**
     * Set CustomerEquipment
     *
     * @param \Entities\CustomerEquipment $customerEquipment
     * @return PatchPanelPortObject
     */
    public function setCustomerEquipment(\Entities\CustomerEquipment $customerEquipment)
    {
        $this->CustomerEquipment = $customerEquipment;
    
        return $this;
    }

    /**
     * Get CustomerEquipment
     *
     * @return \Entities\CustomerEquipment 
     */
    public function getCustomerEquipment()
    {
        return $this->CustomerEquipment;
    }

    /**
     * Set PatchPanelPort
     *
     * @param \Entities\PatchPanelPort $patchPanelPort
     * @return PatchPanelPortObject
     */
    public function setPatchPanelPort(\Entities\PatchPanelPort $patchPanelPort)
    {
        $this->PatchPanelPort = $patchPanelPort;
    
        return $this;
    }

    /**
     * Get PatchPanelPort
     *
     * @return \Entities\PatchPanelPort 
     */
    public function getPatchPanelPort()
    {
        return $this->PatchPanelPort;
    }

    /**
     * Set SwitchPort
     *
     * @param \Entities\SwitchPort $switchPort
     * @return PatchPanelPortObject
     */
    public function setSwitchPort(\Entities\SwitchPort $switchPort)
    {
        $this->SwitchPort = $switchPort;
    
        return $this;
    }

    /**
     * Get SwitchPort
     *
     * @return \Entities\SwitchPort 
     */
    public function getSwitchPort()
    {
        return $this->SwitchPort;
    }

    /**
     * Set PatchPort
     *
     * @param \Entities\PatchPanelPort $patchPort
     * @return PatchPanelPortObject
     */
    public function setPatchPort(\Entities\PatchPanelPort $patchPort = null)
    {
        $this->PatchPort = $patchPort;
    
        return $this;
    }

    /**
     * Get PatchPort
     *
     * @return \Entities\PatchPanelPort 
     */
    public function getPatchPort()
    {
        return $this->PatchPort;
    }
}