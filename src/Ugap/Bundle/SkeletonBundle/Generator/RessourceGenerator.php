<?php

/**
 * 
 * @author MSFAXI
 *
 */

namespace Ugap\Bundle\SkeletonBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

class RessourceGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate($ressourcename, $dir, $projectname)
    {
    	$ucfirstressourcename = ucfirst($ressourcename);  
    	$ucressourcename = strtoupper($ressourcename);
    	$ucprojectname = strtoupper($projectname);
        $parameters = array(
            'ressourcename' => $ressourcename,
        	'projectname' => $projectname,
        	'ucfirstressourcename' => $ucfirstressourcename,
        	'ucressourcename' => $ucressourcename,
        	'ucprojectname' => $ucprojectname,	
        );
        $this->setSkeletonDirs(dirname(dirname(__FILE__)).'/Resources/skeleton');
        
		
        $this->renderFile('ressource/wsx/lib_wsx.php.twig', $dir.'/'.$projectname.'-lib/src/wsx/lib_wsx_'.$ressourcename.'.php', $parameters);
        $this->renderFile('ressource/dao/lib_dao.php.twig', $dir.'/'.$projectname.'-lib/src/dao/lib_dao_'.$ressourcename.'.php', $parameters);
        $this->renderFile('ressource/ref/lib_ref.php.twig', $dir.'/'.$projectname.'-lib/src/ref/lib_dao_'.$ressourcename.'.php', $parameters);
        $this->renderFile('ressource/solr/lib_solr.php.twig', $dir.'/'.$projectname.'-lib/src/solr/lib_solr_'.$ressourcename.'.php', $parameters);
        $this->renderFile('ressource/silo/silo_fct_restful.php.twig', $dir.'/'.$projectname.'-silo/src/fct/silo_fct_restful_'.$ressourcename.'.php', $parameters);
        $this->renderFile('ressource/silo/silo_restful.php.twig', $dir.'/'.$projectname.'-silo/src/restful/silo_restful_'.$ressourcename.'.php', $parameters);
        $dir_silo_file = $dir.'/'.$projectname.'-silo/htdocs/silo.php';
        $content = file_get_contents($dir_silo_file);
        if (substr_count($content, $ressourcename) == 0 ){
        	$content = str_replace("//restful required", "//restful required \nrequire pathFile('silo_restful_$ressourcename.php');", $content);
   			file_put_contents($dir_silo_file, $content);
        }
        
         $this->renderFile('ressource/pilote/pilote_ajax.php.twig', $dir.'/'.$projectname.'-pilote/src/ajax/pilote_ajax_'.$ressourcename.'.php', $parameters);
          $this->renderFile('ressource/pilote/pilote_fct_ajax.php.twig', $dir.'/'.$projectname.'-pilote/src/fct/pilote_fct_ajax_'.$ressourcename.'.php', $parameters);
    }
}
