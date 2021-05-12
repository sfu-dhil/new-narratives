<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Role.
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role extends AbstractTerm {
    /**
     * @var Collection|Contribution[]
     * @ORM\OneToMany(targetEntity="Contribution", mappedBy="role")
     */
    private $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    /**
     * Add contribution.
     *
     * @return Role
     */
    public function addContribution(Contribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution.
     */
    public function removeContribution(Contribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions.
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }
}
