<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatchPanelPort
 */
class PatchPanelPort
{
    /**
     * @var integer
     */
    private $position;

    /**
     * @var string
     */
    private $medium;

    /**
     * @var string
     */
    private $connector;

    /**
     * @var boolean
     */
    private $available_for_use;

    /**
     * @var integer
     */
    private $duplex;

    /**
     * @var string
     */
    private $colo_reference;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var boolean
     */
    private $deleted;

    /**
     * @var \DateTime
     */
    private $deleted_on;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Entities\PatchPanelPortObject
     */
    private $Object;

    /**
     * @var \Entities\PatchPanel
     */
    private $PatchPanel;


    /**
     * Set position
     *
     * @param integer $position
     * @return PatchPanelPort
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set medium
     *
     * @param string $medium
     * @return PatchPanelPort
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;
    
        return $this;
    }

    /**
     * Get medium
     *
     * @return string 
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * Set connector
     *
     * @param string $connector
     * @return PatchPanelPort
     */
    public function setConnector($connector)
    {
        $this->connector = $connector;
    
        return $this;
    }

    /**
     * Get connector
     *
     * @return string 
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * Set available_for_use
     *
     * @param boolean $availableForUse
     * @return PatchPanelPort
     */
    public function setAvailableForUse($availableForUse)
    {
        $this->available_for_use = $availableForUse;
    
        return $this;
    }

    /**
     * Get available_for_use
     *
     * @return boolean 
     */
    public function getAvailableForUse()
    {
        return $this->available_for_use;
    }

    /**
     * Set duplex
     *
     * @param integer $duplex
     * @return PatchPanelPort
     */
    public function setDuplex($duplex)
    {
        $this->duplex = $duplex;
    
        return $this;
    }

    /**
     * Get duplex
     *
     * @return integer 
     */
    public function getDuplex()
    {
        return $this->duplex;
    }

    /**
     * Set colo_reference
     *
     * @param string $coloReference
     * @return PatchPanelPort
     */
    public function setColoReference($coloReference)
    {
        $this->colo_reference = $coloReference;
    
        return $this;
    }

    /**
     * Get colo_reference
     *
     * @return string 
     */
    public function getColoReference()
    {
        return $this->colo_reference;
    }


    /**
     * Set notes
     *
     * @param string $notes
     * @return PatchPanelPort
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return PatchPanelPort
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    
        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleted_on
     *
     * @param \DateTime $deletedOn
     * @return PatchPanelPort
     */
    public function setDeletedOn($deletedOn)
    {
        $this->deleted_on = $deletedOn;
    
        return $this;
    }

    /**
     * Get deleted_on
     *
     * @return \DateTime 
     */
    public function getDeletedOn()
    {
        return $this->deleted_on;
    }

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
     * Set PatchPanel
     *
     * @param \Entities\PatchPanel $patchPanel
     * @return PatchPanelPort
     */
    public function setPatchPanel(\Entities\PatchPanel $patchPanel)
    {
        $this->PatchPanel = $patchPanel;
    
        return $this;
    }

    /**
     * Get PatchPanel
     *
     * @return \Entities\PatchPanel 
     */
    public function getPatchPanel()
    {
        return $this->PatchPanel;
    }
    /**
     * @var \Entities\PatchPanelPortObject
     */
    private $ConnectionProxy;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $PatchPanelPortObjects;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->PatchPanelPortObjects = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set ConnectionProxy
     *
     * @param \Entities\PatchPanelPortObject $connectionProxy
     * @return PatchPanelPort
     */
    public function setConnectionProxy(\Entities\PatchPanelPortObject $connectionProxy = null)
    {
        $this->ConnectionProxy = $connectionProxy;
    
        return $this;
    }

    /**
     * Get ConnectionProxy
     *
     * @return \Entities\PatchPanelPortObject 
     */
    public function getConnectionProxy()
    {
        return $this->ConnectionProxy;
    }

    /**
     * Add PatchPanelPortObjects
     *
     * @param \Entities\PatchPanelPortObject $patchPanelPortObjects
     * @return PatchPanelPort
     */
    public function addPatchPanelPortObject(\Entities\PatchPanelPortObject $patchPanelPortObjects)
    {
        $this->PatchPanelPortObjects[] = $patchPanelPortObjects;
    
        return $this;
    }

    /**
     * Remove PatchPanelPortObjects
     *
     * @param \Entities\PatchPanelPortObject $patchPanelPortObjects
     */
    public function removePatchPanelPortObject(\Entities\PatchPanelPortObject $patchPanelPortObjects)
    {
        $this->PatchPanelPortObjects->removeElement($patchPanelPortObjects);
    }

    /**
     * Get PatchPanelPortObjects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPatchPanelPortObjects()
    {
        return $this->PatchPanelPortObjects;
    }
    /**
     * @var \DateTime
     */
    private $assigned;

    /**
     * @var \DateTime
     */
    private $connected;

    /**
     * @var \DateTime
     */
    private $cancelled;


    /**
     * Set assigned
     *
     * @param \DateTime $assigned
     * @return PatchPanelPort
     */
    public function setAssigned($assigned)
    {
        $this->assigned = $assigned;
    
        return $this;
    }

    /**
     * Get assigned
     *
     * @return \DateTime 
     */
    public function getAssigned()
    {
        return $this->assigned;
    }

    /**
     * Set connected
     *
     * @param \DateTime $connected
     * @return PatchPanelPort
     */
    public function setConnected($connected)
    {
        $this->connected = $connected;
    
        return $this;
    }

    /**
     * Get connected
     *
     * @return \DateTime 
     */
    public function getConnected()
    {
        return $this->connected;
    }

    /**
     * Set cancelled
     *
     * @param \DateTime $cancelled
     * @return PatchPanelPort
     */
    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;
    
        return $this;
    }

    /**
     * Get cancelled
     *
     * @return \DateTime 
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }
}