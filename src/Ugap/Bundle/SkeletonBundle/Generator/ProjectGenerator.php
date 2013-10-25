<?php

/**
 * 
 * @author MSFAXI
 *
 */

namespace Ugap\Bundle\SkeletonBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

class ProjectGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate($projectname,$dir, $format, $structure)
    {	
    	$dirRoot = $dir;
        $dir .= '/'.strtr($projectname, '\\', '/');
        
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the project as the target directory "%s" exists but is a file.', realpath($dir)));
            }
            $files = scandir($dir);
            if ($files != array('.', '..')) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dir)));
            }
            if (!is_writable($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not writable.', realpath($dir)));
            }
        }
		
        $ucfirstprojectname = ucfirst($projectname);  
    	$ucprojectname = strtoupper($projectname);
    	
        $parameters = array(
            'projectname' => $projectname,
        	'ucfirstprojectname' =>$ucfirstprojectname,
        	'ucprojectname' =>$ucprojectname,
        );
        $this->setSkeletonDirs(dirname(dirname(__FILE__)).'/Resources/skeleton');
        
    	
            $this->filesystem->mkdir($dir.'/config');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-cli/config/');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-cli/src/batch');
            
            $this->filesystem->mkdir($dir.'/'.$projectname.'-ihm/tools');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-ihm/www');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-ihm/www-prod');
            
            $this->filesystem->mkdir($dir.'/'.$projectname.'-install/apache');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-install/bin');
            
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/bin');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/acl');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/wsx');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/auth');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/guid');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/ref');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/util');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/dao');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-lib/src/solr');
            
            
            $this->filesystem->mkdir($dir.'/'.$projectname.'-silo/config');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-silo/htdocs');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-silo/src/fct');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-silo/src/restful');
            
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/config');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/htdocs');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/src/session');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/src/fct');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/src/ajax');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/src/guid');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/src/mail');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-pilote/src/ref');
            
            $this->filesystem->mkdir($dir.'/'.$projectname.'-solr');
            
            $this->filesystem->mkdir($dir.'/'.$projectname.'-test/js');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-test/php');
            $this->filesystem->mkdir($dir.'/'.$projectname.'-test/w3c');
            
			$this->renderFile('project/install/apache.conf.twig', $dir.'/'.$projectname.'-install/apache/'.$projectname.'.conf', $parameters);
	        $this->renderFile('project/install/ssl.conf.twig', $dir.'/'.$projectname.'-install/apache/'.$projectname.'.ssl.conf', $parameters);
			
	        
	        $this->renderFile('project/config/config.php.twig', $dir.'/config/config.php', $parameters);
	        $this->renderFile('project/config/htaccess.twig', $dir.'/config/.htaccess', $parameters);
			$this->renderFile('project/config/platform.php.twig', $dir.'/config/platform.php', $parameters);
			$this->renderFile('project/config/project.ini.twig', $dir.'/config/'.$projectname.'.ini', $parameters);
			$this->renderFile('project/config/project.secret.ini.twig', $dir.'/config/'.$projectname.'.secret.ini', $parameters);
			$this->renderFile('project/config/project.uri.ini.twig', $dir.'/config/'.$projectname.'.uri.ini', $parameters);
			$this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-silo/.htaccess', $parameters);
	        $this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-silo/config/.htaccess', $parameters);
	        $this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-lib/.htaccess', $parameters);
	        $this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-pilote/src/.htaccess', $parameters);
            $this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-pilote/config/.htaccess', $parameters);
	        $this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-cli/.htaccess', $parameters);
	        $this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-cli/config/.htaccess', $parameters);
	      //$this->renderFile('project/config/htaccess.twig', $dir.'/'.$projectname.'-pilote/.htaccess', $parameters); 
	      
	        $this->renderFile('project/project-cli/config/config.php.twig', $dir.'/'.$projectname.'-cli/config/config.php', $parameters);
	        $this->renderFile('project/project-cli/config/http.code.ini.twig', $dir.'/'.$projectname.'-cli/config/httpd.code.ini', $parameters);
	        $this->renderFile('project/project-cli/config/properties.ini.twig', $dir.'/'.$projectname.'-cli/config/properties.ini', $parameters);
	        $this->renderFile('project/project-cli/src/preprocess.php.twig', $dir.'/'.$projectname.'-cli/src/preprocess.php', $parameters);
	        
	        $this->renderFile('project/index.html.twig', $dir.'/'.$projectname.'-ihm/www/index.html', $parameters);
	        $this->renderFile('project/index.html.twig', $dir.'/'.$projectname.'-ihm/www-prod/index.html', $parameters);
	        $this->renderFile('project/htaccess.twig', $dir.'/.htaccess', $parameters);
	       
	       
	     
	      
	        $this->renderFile('project/pilote/config/config.php.twig', $dir.'/'.$projectname.'-pilote/config/config.php', $parameters);
	        $this->renderFile('project/pilote/config/http.code.ini.twig', $dir.'/'.$projectname.'-pilote/config/http.code.ini', $parameters);
	        $this->renderFile('project/pilote/config/properties.ini.twig', $dir.'/'.$projectname.'-pilote/config/properties.ini', $parameters);
	        $this->renderFile('project/pilote/htdocs/ajax.php.twig', $dir.'/'.$projectname.'-pilote/htdocs/ajax.php', $parameters);
	        $this->renderFile('project/pilote/htdocs/logout.php.twig', $dir.'/'.$projectname.'-pilote/htdocs/logout.php', $parameters);
	        $this->renderFile('project/pilote/src/preprocess.apache.php.twig', $dir.'/'.$projectname.'-pilote/src/preprocess.apache.php', $parameters);
	        $this->renderFile('project/pilote/control.php.twig', $dir.'/'.$projectname.'-pilote/control.php', $parameters);
	        $this->renderFile('project/pilote/src/session/pilote_session.php.twig', $dir.'/'.$projectname.'-pilote/src/session/pilote_session_'.$projectname.'.php', $parameters);
	        $this->renderFile('project/pilote/src/ajax/pilote_ajax_acl.php.twig', $dir.'/'.$projectname.'-pilote/src/ajax/pilote_ajax_acl.php', $parameters);
			$this->renderFile('project/pilote/src/ajax/pilote_ajax_droit.php.twig', $dir.'/'.$projectname.'-pilote/src/ajax/pilote_ajax_droit.php', $parameters);
			$this->renderFile('project/pilote/src/ajax/pilote_ajax_profil.php.twig', $dir.'/'.$projectname.'-pilote/src/ajax/pilote_ajax_profil.php', $parameters);
			$this->renderFile('project/pilote/src/ajax/pilote_ajax_ressource.php.twig', $dir.'/'.$projectname.'-pilote/src/ajax/pilote_ajax_ressource.php', $parameters);
			$this->renderFile('project/pilote/src/ajax/pilote_ajax_search.php.twig', $dir.'/'.$projectname.'-pilote/src/ajax/pilote_ajax_search.php', $parameters);
			
			$this->renderFile('project/pilote/src/fct/pilote_fct_ajax_acl.php.twig', $dir.'/'.$projectname.'-pilote/src/fct/pilote_fct_ajax_acl.php', $parameters);
			$this->renderFile('project/pilote/src/fct/pilote_fct_ajax_droit.php.twig', $dir.'/'.$projectname.'-pilote/src/fct/pilote_fct_ajax_droit.php', $parameters);
			$this->renderFile('project/pilote/src/fct/pilote_fct_ajax_profil.php.twig', $dir.'/'.$projectname.'-pilote/src/fct/pilote_fct_ajax_profil.php', $parameters);
			$this->renderFile('project/pilote/src/fct/pilote_fct_ajax_ressource.php.twig', $dir.'/'.$projectname.'-pilote/src/fct/pilote_fct_ajax_ressource.php', $parameters);
			$this->renderFile('project/pilote/src/fct/pilote_fct_ajax_search.php.twig', $dir.'/'.$projectname.'-pilote/src/fct/pilote_fct_ajax_search.php', $parameters);
			
			
			
	        $this->renderFile('project/silo/config/config.php.twig', $dir.'/'.$projectname.'-silo/config/config.php', $parameters);
	        $this->renderFile('project/silo/config/properties.ini.twig', $dir.'/'.$projectname.'-silo/config/properties.ini', $parameters);
	        $this->renderFile('project/silo/htdocs/silo.php.twig', $dir.'/'.$projectname.'-silo/htdocs/silo.php', $parameters);
	        $this->renderFile('project/silo/src/preprocess.apache.php.twig', $dir.'/'.$projectname.'-silo/src/preprocess.apache.php', $parameters);
	        $this->renderFile('project/silo/control.php.twig', $dir.'/'.$projectname.'-silo/control.php', $parameters);
	      	
			$this->renderFile('project/silo/src/fct/silo_fct_restful_acl_droit.php.twig', $dir.'/'.$projectname.'-silo/src/fct/silo_fct_restful_acl_droit.php', $parameters);
			$this->renderFile('project/silo/src/fct/silo_fct_restful_acl_profil.php.twig', $dir.'/'.$projectname.'-silo/src/fct/silo_fct_restful_acl_profil.php', $parameters);
			$this->renderFile('project/silo/src/fct/silo_fct_restful_acl_ressource.php.twig', $dir.'/'.$projectname.'-silo/src/fct/silo_fct_restful_acl_ressource.php', $parameters);
			$this->renderFile('project/silo/src/fct/silo_fct_restful_acl.php.twig', $dir.'/'.$projectname.'-silo/src/fct/silo_fct_restful_acl.php', $parameters);
			$this->renderFile('project/silo/src/fct/silo_fct_restful_search.php.twig', $dir.'/'.$projectname.'-silo/src/fct/silo_fct_restful_search.php', $parameters);
			$this->renderFile('project/silo/src/restful/silo_restful_droit.php.twig', $dir.'/'.$projectname.'-silo/src/restful/silo_restful_droit.php', $parameters);
			$this->renderFile('project/silo/src/restful/silo_restful_profil.php.twig', $dir.'/'.$projectname.'-silo/src/restful/silo_restful_profil.php', $parameters);
			$this->renderFile('project/silo/src/restful/silo_restful_ressource.php.twig', $dir.'/'.$projectname.'-silo/src/restful/silo_restful_ressource.php', $parameters);
			$this->renderFile('project/silo/src/restful/silo_restful_acl.php.twig', $dir.'/'.$projectname.'-silo/src/restful/silo_restful_acl.php', $parameters);
			$this->renderFile('project/silo/src/restful/silo_restful_search.php.twig', $dir.'/'.$projectname.'-silo/src/restful/silo_restful_search.php', $parameters);
			
			
	        
	       
	        $this->renderFile('project/lib/acl/lib_acl_associations.php.twig', $dir.'/'.$projectname.'-lib/src/acl/lib_acl_associations.php', $parameters);
	        $this->renderFile('project/lib/acl/lib_acl_droits.php.twig', $dir.'/'.$projectname.'-lib/src/acl/lib_acl_droits.php', $parameters);
	        $this->renderFile('project/lib/acl/lib_acl_profils.php.twig', $dir.'/'.$projectname.'-lib/src/acl/lib_acl_profils.php', $parameters);
	        $this->renderFile('project/lib/acl/lib_acl_ressources.php.twig', $dir.'/'.$projectname.'-lib/src/acl/lib_acl_ressources.php', $parameters);
	        $this->renderFile('project/lib/auth/lib_auth_wsx_http_basic.php.twig', $dir.'/'.$projectname.'-lib/src/auth/lib_auth_wsx_http_basic.php', $parameters);
	        $this->renderFile('project/lib/bin/batch.cli.conf.twig', $dir.'/'.$projectname.'-lib/src/bin/batch.cli.conf', $parameters);
	      //$this->renderFile('project/lib/bin/batch.sh.twig', $dir.'/'.$projectname.'-lib/bin/batch.sh', $parameters);
	        $this->renderFile('project/lib/bin/cli_admin_batch.php.twig', $dir.'/'.$projectname.'-lib/src/bin/cli_admin_batch.php', $parameters);
	      //$this->renderFile('project/lib/bin/cli_admin_batch5.sh.twig', $dir.'/'.$projectname.'-lib/bin/cli_admin_batch5.sh', $parameters);
	      //$this->renderFile('project/lib/bin/cli_admin_config5.sh.twig', $dir.'/'.$projectname.'-lib/bin/cli_admin_config5.sh', $parameters);
	      //$this->renderFile('project/lib/bin/cli_admin_log5.sh.twig', $dir.'/'.$projectname.'-lib/bin/cli_admin_log55.sh', $parameters);
	        $this->renderFile('project/lib/guid/lib_guid_solr.php.twig', $dir.'/'.$projectname.'-lib/src/guid/lib_guid_solr.php', $parameters);
	        $this->renderFile('project/lib/ref/lib_ref_acl.php.twig', $dir.'/'.$projectname.'-lib/src/ref/lib_ref_acl.php', $parameters);
	        $this->renderFile('project/lib/util/lib_util_logfile.php.twig', $dir.'/'.$projectname.'-lib/src/util/lib_util_logfile.php', $parameters);
	        $this->renderFile('project/lib/util/lib_util_preprocess.php.twig', $dir.'/'.$projectname.'-lib/src/util/lib_util_preprocess.php', $parameters);
	        $this->renderFile('project/lib/dao/lib_dao_acl_droit.php.twig', $dir.'/'.$projectname.'-lib/src/dao/lib_dao_acl_droit.php', $parameters);
	        $this->renderFile('project/lib/dao/lib_dao_acl_profil.php.twig', $dir.'/'.$projectname.'-lib/src/dao/lib_dao_acl_profil.php', $parameters);
	        $this->renderFile('project/lib/dao/lib_dao_acl_ressource.php.twig', $dir.'/'.$projectname.'-lib/src/dao/lib_dao_acl_ressource.php', $parameters);
	        $this->renderFile('project/lib/dao/lib_dao_acl.php.twig', $dir.'/'.$projectname.'-lib/src/dao/lib_dao_acl.php', $parameters);
	        $this->renderFile('project/lib/dao/lib_dao_bdd_solr.php.twig', $dir.'/'.$projectname.'-lib/src/dao/lib_dao_bdd_solr.php', $parameters);
			$this->renderFile('project/lib/dao/lib_dao_require.php.twig', $dir.'/'.$projectname.'-lib/src/dao/lib_dao_require.php', $parameters);	        
			$this->renderFile('project/lib/solr/lib_solr_acl.php.twig', $dir.'/'.$projectname.'-lib/src/solr/lib_solr_acl.php', $parameters);
			$this->renderFile('project/lib/solr/lib_solr_search.php.twig', $dir.'/'.$projectname.'-lib/src/solr/lib_solr_search.php', $parameters);
			$this->renderFile('project/lib/wsx/lib_wsx_acl_droit.php.twig', $dir.'/'.$projectname.'-lib/src/wsx/lib_wsx_acl_droit.php', $parameters);
			$this->renderFile('project/lib/wsx/lib_wsx_acl_profil.php.twig', $dir.'/'.$projectname.'-lib/src/wsx/lib_wsx_acl_profil.php', $parameters);
			$this->renderFile('project/lib/wsx/lib_wsx_acl_ressource.php.twig', $dir.'/'.$projectname.'-lib/src/wsx/lib_wsx_acl_ressource.php', $parameters);
			$this->renderFile('project/lib/wsx/lib_wsx_acl.php.twig', $dir.'/'.$projectname.'-lib/src/wsx/lib_wsx_acl.php', $parameters);
			$this->renderFile('project/lib/wsx/lib_wsx_search.php.twig', $dir.'/'.$projectname.'-lib/src/wsx/lib_wsx_search.php', $parameters);
			$this->renderFile('project/lib/wsx/lib_wsx_solr.php.twig', $dir.'/'.$projectname.'-lib/src/wsx/lib_wsx_solr.php', $parameters);
			
			
			
			$this->renderFile('ressource/silo/htdocs/silo.php.twig', $dir.'/'.$projectname.'-silo/htdocs/silo.php', $parameters);
			
			
	        $this->filesystem->symlink($dir, '/var/www/'.$projectname);
	        $this->filesystem->symlink('/var/www/'.$projectname.'/'.$projectname.'-install/apache/'.$projectname.'.conf', '/etc/apache2/sites-available/'.$projectname.'.conf');
	        $this->filesystem->symlink('/etc/apache2/sites-available/'.$projectname.'.conf', '/etc/apache2/sites-enabled/'.$projectname.'.conf');
	        
	        $this->filesystem->mirror($dirRoot.'/install/solr/collection',$dir.'/'.$projectname.'-solr/');
	        $this->renderFile('project/solr/project-solr.xml.twig', '/etc/tomcat6/Catalina/localhost/'.$projectname.'-solr.xml', $parameters);
	        $this->renderFile('project/solr/solrcore.properties.twig', $dir.'/'.$projectname.'-solr/collection1/conf/solrcore.properties', $parameters);
	        $this->filesystem->mkdir('/data/'.$projectname.'-solr');
	        $this->filesystem->chown('/data/'.$projectname.'-solr','tomcat6');
    }
}
