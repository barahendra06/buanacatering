<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $fillable = [
        'name', 'email', 'password',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    protected $appends = array('number', 'balance_rupiah');
    
    public function findForPassport($username) {
        return $this->where('email', $username)->first();
    }

    // one user has one member
    public function member()
    {
        return $this->hasOne('App\Member');
    }

    // many users is owned by one role
    public function role()
    {
        return $this->belongsTo('App\Roles');
    }

    // one account can have many guardians
    public function guardians()
    {
        return $this->hasMany('App\Guardian');
    }

    // one user has many students
    public function students()
    {
        return $this->hasMany('App\Student');
    }

    // one user has many invoices through students
    public function invoices()
    {
        return $this->hasManyThrough('App\Invoice', 'App\Student');
    }    

    //user can have many invoice history
    public function invoiceHistories()
    {
        return $this->hasMany('App\InvoiceHistory');
    } 

    //user can have many payment history
    public function paymentHistories()
    {
        return $this->hasMany('App\PaymentHistory');
    }

    //user can have many ledger
    public function ledgers()
    {
        return $this->hasMany('App\Ledger');
    }

    //user can have many ledgers
    public function payableLedgers()
    {
        return $this->hasMany(Ledger::class, 'user_id', 'id')
            ->where('ledger_account_id', LEDGER_ACCOUNT_PAYABLE);
    }

    // many users is owned by one role
    public function sales()
    {
        return $this->belongsTo('App\Sales');
    }

    public function coach()
    {
        return $this->hasOne(Coach::class);
    }

    //user can have many refund
    public function userRefunds()
    {
        return $this->hasMany('App\UserRefund');
    }

    //user can have many cancellation
    public function userCancellations()
    {
        return $this->hasMany('App\UserCancellation');
    }

    //user can have many student note
    public function studentNotes()
    {
        return $this->hasMany('App\studentNotes');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'user_branch', 'user_id', 'branch_id','id', 'id');
    }

    //Super Admin can login as any user, use this only for debug purpose
    public function isSuperAdmin()
    {
        if($this->role_id == ROLE_SUPER_ADMIN)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Admin can changes literally ALL the data without any bounds
    public function isAdmin()
    {
        if($this->role_id == ROLE_ADMIN or $this->role_id == ROLE_SUPER_ADMIN)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isMember()
    {
        if($this->role_id == ROLE_MEMBER)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find editor
    public function isEditor()
    {
        if($this->role_id == ROLE_EDITOR)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find contributor
    public function isContributor()
    {
        if($this->role_id == ROLE_CONTRIBUTOR)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find finance
    public function isFinance()
    {
        if($this->role_id == ROLE_FINANCE || $this->role_id == ROLE_INVESTOR_FINANCE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find operational
    public function isOperational()
    {
        if($this->role_id == ROLE_OPERATIONAL)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find operational (Student Care)
    public function isStudentCare()
    {
        if($this->role_id == ROLE_OPERATIONAL && $this->branches->count())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find manager
    public function isManager()
    {
        if($this->role_id == ROLE_MANAGER)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find coach
    public function isCoach()
    {
        if($this->role_id == ROLE_COACH)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find coach
    public function isHeadCoach()
    {
        if($this->role_id == ROLE_HEAD_COACH)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find coach
    public function isFreelanceCoach()
    {
        if($this->role_id == ROLE_FREELANCE_COACH)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //find investor
    public function isInvestor()
    {
        if($this->role_id == ROLE_INVESTOR)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isInvestorFinance()
    {
        if($this->role_id == ROLE_INVESTOR_FINANCE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isConfirmed()
    {
        if($this->confirmed == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function scopeConfirmed($query)
    {
        return $query->where('confirmed',1);
    }   

    public function scopeNotExpired($query)
    {
        return $query->where('expired',0);
    } 

    public function scopeExpired($query)
    {
        return $query->where('expired',1);
    }   

    public function scopeMember($query)
    {
        return $query->where('role_id',ROLE_MEMBER);
    }  

    public function scopeCoach($query)
    {
        return $query->where('role_id',ROLE_COACH);
    }  

    public function scopeContributor($query)
    {
        return $query->where('role_id',ROLE_CONTRIBUTOR);
    }  

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function scopeRenter($query)
    {
        return $query->where('role_id',ROLE_RENTER);
    }

    public function scopeAllowNewsletter($query)
    {
        return $query->where('allow_newsletter',1);
    }     
    
    //------------------------------------------- GETTER -----------------------------------
    public function getNumberAttribute()
    {
        return "ACC".str_pad($this->id, 7, "0", STR_PAD_LEFT);
    }

    public function getBalanceRupiahAttribute()
    {
        return "Rp " . number_format($this->balance,0,',','.');
    }
}
