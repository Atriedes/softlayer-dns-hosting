<?php

class DNS extends Eloquent {

    protected $table = 'domains';
    protected $primaryKey = 'domain_id';

    public function records() {

    	return $this->hasMany('Record', 'domain_id');

    }

}