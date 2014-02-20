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
     * @var \DateTime
     */
    private $ordered;

    /**
     * @var \DateTime
     */
    private $completed;

    /**
     * @var \DateTime
     */
    private $ceased;

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
     * @var \Entities\PatchPanelPortObject
     */
    private $PatchPanelPortObject;

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
     * Set ordered
     *
     * @param \DateTime $ordered
     * @return PatchPanelPort
     */
    public function setOrdered($ordered)
    {
        $this->ordered = $ordered;
    
        return $this;
    }

    /**
     * Get ordered
     *
     * @return \DateTime 
     */
    public function getOrdered()
    {
        return $this->ordered;
    }

    /**
     * Set completed
     *
     * @param \DateTime $completed
     * @return PatchPanelPort
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    
        return $this;
    }

    /**
     * Get completed
     *
     * @return \DateTime 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set ceased
     *
     * @param \DateTime $ceased
     * @return PatchPanelPort
     */
    public function setCeased($ceased)
    {
        $this->ceased = $ceased;
    
        return $this;
    }

    /**
     * Get ceased
     *
     * @return \DateTime 
     */
    public function getCeased()
    {
        return $this->ceased;
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
     * Set Object
     *
     * @param \Entities\PatchPanelPortObject $object
     * @return PatchPanelPort
     */
    public function setObject(\Entities\PatchPanelPortObject $object)
    {
        $this->Object = $object;
    
        return $this;
    }

    /**
     * Get Object
     *
     * @return \Entities\PatchPanelPortObject 
     */
    public function getObject()
    {
        return $this->Object;
    }

    /**
     * Set PatchPanelPortObject
     *
     * @param \Entities\PatchPanelPortObject $patchPanelPortObject
     * @return PatchPanelPort
     */
    public function setPatchPanelPortObject(\Entities\PatchPanelPortObject $patchPanelPortObject = null)
    {
        $this->PatchPanelPortObject = $patchPanelPortObject;
    
        return $this;
    }

    /**
     * Get PatchPanelPortObject
     *
     * @return \Entities\PatchPanelPortObject 
     */
    public function getPatchPanelPortObject()
    {
        return $this->PatchPanelPortObject;
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
}