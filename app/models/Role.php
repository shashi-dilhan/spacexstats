<?php 
class Role extends Eloquent {

	protected $table = 'roles';
	protected $primaryKey = 'role_id';
    protected $timestamps = false;

    protected $hidden = [];
    protected $appends = [];
    protected $fillable = [];
    protected $guarded = [];

	// Relations
	public function user() {
		return $this->hasMany('users');
	}

}