<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['Heresay'] = array(
   'Name' => 'Heresay',
   'Description' => 'A plugin that lets you add custom pages. You need to add them to the "pages" folder of this plugin.',
   'Version' => '1',
   'Author' => "Mark O'Sullivan",
   'AuthorEmail' => 'mark@vanillaforums.com',
   'AuthorUrl' => 'http://vanillaforums.com'
);

class HeresayPlugin implements Gdn_IPlugin {

   /**
* Adds the "default" page to the dashboard side menu.
*/
   public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Site Settings', 'Custom Pages', 'plugin/page/default/admin');
   }
   
   public function PluginController_Page_Create(&$Sender) {
	
		$Sender->Head->Title('The Page You requested could not be found on this server');
		$Sender->AddModule('SignedInModule');
		$Sender->AddModule('GuestModule');
		
		// See what page was requested
		$Page = ArrayValue('0', $Sender->RequestArgs, 'default');
		$MasterView = ArrayValue('1', $Sender->RequestArgs, 'default');
		$MasterView = $MasterView != 'admin' ? 'default' : 'admin';
		$Path = PATH_PLUGINS . DS . 'Heresay' . DS . 'pages' . DS;
		
		// If the page doesn't exist, roll back to the default
		if (!file_exists($Path.$Page.'.php'))
		$Page = 'default';

		// Use the default css if not viewing admin master
		if ($MasterView != 'admin') {
			$Sender->ClearCssFiles();
			$Sender->AddCssFile('style.css');
		} else {
			$Sender->AddSideMenu('plugin/page/default/admin');
		}

		$Sender->AddSideMenu();

		$Sender->MasterView = $MasterView;
      	$Sender->Render($Path.$Page.'.php');
   }

public function Base_Render_Before(&$Sender) {
$heresay = '<script type="text/javascript" src="http://test.heresay.org.uk/platform/heresay_vanilla_test.js" ></script>';
$googleMaps = '<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>';
// Send it to the Header of the page
$Sender->Head->AddString($heresay);
$Sender->Head->AddString($googleMaps);
}

   public function Setup() {
      // No setup required.
   }
}