<?php

namespace neilherbertuk\celcatwebapi\Traits;

use neilherbertuk\celcatwebapi\Classes\Groups;
use neilherbertuk\celcatwebapi\Classes\Rooms;
use neilherbertuk\celcatwebapi\Classes\StudentMembership;
use neilherbertuk\celcatwebapi\Classes\Students;

trait ResourcesTrait
{
    /**
     * Rooms Resource Instance
     * @return Rooms
     */
    public function rooms()
    {
        return new Rooms($this);
    }

    /**
     * Students Resource Instance
     * @return Students
     */
    public function students()
    {
        return new Students($this);
    }

    /**
     * Groups Resource Instance
     * @return Students
     */
    public function groups()
    {
        return new Groups($this);
    }

    /**
     * Groups Resource Instance
     * @return Students
     */
    public function studentMembership()
    {
        return new StudentMembership($this);
    }



}