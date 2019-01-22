<? 
use Closure;
use App\Model\Domain;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifiedDomain {

    protected $Domain;

    public function handle($request, Closure $next){

        $domain = $_SERVER['SERVER_NAME'];
        $Domain = Domain::where('domain', $domain)->first();

        if(!$Domain){ echo 123 }

        $Site = $Domain->site;

        if(!$Site){
            echo 456; 
        }

        $this->Domain = $Domain;

        return $next($request);
    }

    public function getDomain(){
        return $this->Domain;
    }

    public function getSite(){
        return $this->Domain->site;
    }

}