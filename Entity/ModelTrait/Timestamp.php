<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Entity\ModelTrait;

/**
 * SFL/BusinessDirectory - Symfony3 business directory
 *
 *  Copyright 2017 by Savoir-faire Linux
 *
 * This file is part of SFL/BusinessDirectory application.
 * 
 * SFL/BusinessDirectory application is free software: you can redistribute 
 * it and/or modify it under the terms of the GNU General Public 
 * License as published by the Free Software Foundation, either 
 * version 3 of the License, or (at your option) any later version.
 * 
 * SFL/BusinessDirectory application is distributed in the hope that it will 
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with SFL/BusinessDirectory.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * @ORM\HasLifecycleCallbacks
 */
trait Timestamp {

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;


    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function timestampPrePersist() {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    /**
     * @ORM\PreUpdate()
     */
    private function timestampPreUpdate(){
        if(!@$this->cancelUpdatedAtTrigger) {
            $this->updatedAt = new \DateTime;
        }
        $this->setCancelUpdatedAtTrigger(false);
    }


    private $cancelUpdatedAtTrigger;

    public function getCancelUpdatedAtTrigger() {
        return $this->cancelUpdatedAtTrigger;
    }

    public function setCancelUpdatedAtTrigger($cancelUpdatedAtTrigger) {
        $this->cancelUpdatedAtTrigger = $cancelUpdatedAtTrigger;
    }

}
