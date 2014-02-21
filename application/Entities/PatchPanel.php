<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatchPanel
 */
class PatchPanel
{


    const MEDIUM_UTP        = 'utp';
    const MEDIUM_SMF        = 'smf';
    const MEDIUM_MMF        = 'mmf';
    const MEDIUM_MIXED      = 'mixed';
    
    public static $MEDIA = [
        self::MEDIUM_UTP        => 'UTP',
        self::MEDIUM_SMF        => 'SMF',
        self::MEDIUM_MMF        => 'MMF',
        self::MEDIUM_MIXED      => 'Mixed'
    ];

    const CONNECTOR_RJ45    = 'RJ45';
    const CONNECTOR_SC      = 'SC';
    const CONNECTOR_LC      = 'LC';
    const CONNECTOR_MU      = 'MU';

    public static $CONNECTORS = [
        self::CONNECTOR_RJ45   => 'RJ45',
        self::CONNECTOR_SC     => 'SC',
        self::CONNECTOR_LC     => 'LC',
        self::CONNECTOR_MU     => 'MU',
    ];

    /**
     * @var string
     */
    private $name;

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
    private $duplex_allowed;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var integer
     */
    private $u_position;

    /**
     * @var \DateTime
     */
    private $installed;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $PatchPanelPorts;

    /**
     * @var \Entities\Cabinet
     */
    private $Cabinet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->PatchPanelPorts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return PatchPanel
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set medium
     *
     * @param string $medium
     * @return PatchPanel
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
     * @return PatchPanel
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
     * Set duplex_allowed
     *
     * @param boolean $duplexAllowed
     * @return PatchPanel
     */
    public function setDuplexAllowed($duplexAllowed)
    {
        $this->duplex_allowed = $duplexAllowed;
    
        return $this;
    }

    /**
     * Get duplex_allowed
     *
     * @return boolean 
     */
    public function getDuplexAllowed()
    {
        return $this->duplex_allowed;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return PatchPanel
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
     * Set u_position
     *
     * @param integer $uPosition
     * @return PatchPanel
     */
    public function setUPosition($uPosition)
    {
        $this->u_position = $uPosition;
    
        return $this;
    }

    /**
     * Get u_position
     *
     * @return integer 
     */
    public function getUPosition()
    {
        return $this->u_position;
    }

    /**
     * Set installed
     *
     * @param \DateTime $installed
     * @return PatchPanel
     */
    public function setInstalled($installed)
    {
        $this->installed = $installed;
    
        return $this;
    }

    /**
     * Get installed
     *
     * @return \DateTime 
     */
    public function getInstalled()
    {
        return $this->installed;
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
     * Add PatchPanelPorts
     *
     * @param \Entities\PatchPanelPort $patchPanelPorts
     * @return PatchPanel
     */
    public function addPatchPanelPort(\Entities\PatchPanelPort $patchPanelPorts)
    {
        $this->PatchPanelPorts[] = $patchPanelPorts;
    
        return $this;
    }

    /**
     * Remove PatchPanelPorts
     *
     * @param \Entities\PatchPanelPort $patchPanelPorts
     */
    public function removePatchPanelPort(\Entities\PatchPanelPort $patchPanelPorts)
    {
        $this->PatchPanelPorts->removeElement($patchPanelPorts);
    }

    /**
     * Get PatchPanelPorts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPatchPanelPorts()
    {
        return $this->PatchPanelPorts;
    }

    /**
     * Set Cabinet
     *
     * @param \Entities\Cabinet $cabinet
     * @return PatchPanel
     */
    public function setCabinet(\Entities\Cabinet $cabinet)
    {
        $this->Cabinet = $cabinet;
    
        return $this;
    }

    /**
     * Get Cabinet
     *
     * @return \Entities\Cabinet 
     */
    public function getCabinet()
    {
        return $this->Cabinet;
    }

    /**
     * Is this patch panel of mixed medium?
     * 
     * @return boolean 
     */
    public function isMediaMixed()
    {
        return $this->getMedium() == self::MEDIUM_MIXED;
    }
}