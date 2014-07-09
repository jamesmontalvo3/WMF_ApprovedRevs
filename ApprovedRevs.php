<?php

if ( !defined( 'MEDIAWIKI' ) ) die();

/**
 * @file
 * @ingroup Extensions
 *
 * @author Yaron Koren
 */

define( 'APPROVED_REVS_VERSION', '1.0.0' );

// credits
$wgExtensionCredits['other'][] = array(
	'path'            => __FILE__,
	'name'            => 'Approved Revs',
	'version'         => APPROVED_REVS_VERSION,
	'author'          => array( 'Yaron Koren', '...' ),
	'url'             => 'https://www.mediawiki.org/wiki/Extension:Approved_Revs',
	'descriptionmsg'  => 'approvedrevs-desc'
);

// global variables
$egApprovedRevsIP = __DIR__ . '/';
$egApprovedRevsBlankIfUnapproved = false;
$egApprovedRevsAutomaticApprovals = true;
$egApprovedRevsShowApproveLatest = false;
$egApprovedRevsShowNotApprovedMessage = false;

// default permissions:
//   * Group:sysop can approve anything approvable
//   * Namespaces Main, User, Template, Help and Project are approvable
//     with no additional approvers
//   * No categories or pages are approvable (unless they're within one
//     of the above namespaces)
$egApprovedRevsPermissions = array (

    'All Pages' => array( 'group' => 'sysop' ),

    'Namespace Permissions' => array (
        NS_MAIN => array(),
        NS_USER => array(),
        NS_TEMPLATE => array(),
        NS_HELP => array(),
        NS_PROJECT => array(),
    ),

    'Category Permissions' => array (),

    'Page Permissions' => array ()

);



// internationalization
$wgMessagesDirs['ApprovedRevs'] = $egApprovedRevsIP . 'i18n';
$wgExtensionMessagesFiles['ApprovedRevs'] = $egApprovedRevsIP . 'ApprovedRevs.i18n.php';
$wgExtensionMessagesFiles['ApprovedRevsAlias'] = $egApprovedRevsIP . 'ApprovedRevs.alias.php';
$wgExtensionMessagesFiles['ApprovedRevsMagic'] = $egApprovedRevsIP . 'ApprovedRevs.i18n.magic.php';

// autoload classes
$wgAutoloadClasses['ApprovedRevs'] = $egApprovedRevsIP . 'ApprovedRevs_body.php';
$wgAutoloadClasses['ApprovedRevsHooks'] = $egApprovedRevsIP . 'ApprovedRevs.hooks.php';
$wgAutoloadClasses['SpecialApprovedPages'] = $egApprovedRevsIP . 'specials/SpecialApprovedPages.php';
$wgAutoloadClasses['SpecialApprovedFiles'] = $egApprovedRevsIP . 'specials/SpecialApprovedFiles.php';
$wgAutoloadClasses['SpecialApprovedPagesQueryPage'] = $egApprovedRevsIP . 'specials/SpecialApprovedPagesQueryPage.php';
$wgAutoloadClasses['SpecialApprovedFilesQueryPage'] = $egApprovedRevsIP . 'specials/SpecialApprovedFilesQueryPage.php';

// special pages
$wgSpecialPages['ApprovedPages'] = 'SpecialApprovedPages';
$wgSpecialPages['ApprovedFiles'] = 'SpecialApprovedFiles';
$wgSpecialPageGroups['ApprovedPages'] = 'pages';
$wgSpecialPageGroups['ApprovedFiles'] = 'pages';

// hooks
$wgHooks['ArticleEditUpdates'][] = 'ApprovedRevsHooks::updateLinksAfterEdit';
$wgHooks['ArticleSaveComplete'][] = 'ApprovedRevsHooks::setLatestAsApproved';
$wgHooks['ArticleSaveComplete'][] = 'ApprovedRevsHooks::setSearchText';
$wgHooks['SearchResultInitFromTitle'][] = 'ApprovedRevsHooks::setSearchRevisionID';
$wgHooks['PersonalUrls'][] = 'ApprovedRevsHooks::removeRobotsTag';
$wgHooks['ArticleFromTitle'][] = 'ApprovedRevsHooks::showApprovedRevision';
$wgHooks['ArticleAfterFetchContent'][] = 'ApprovedRevsHooks::showBlankIfUnapproved';
// MW 1.21+
$wgHooks['ArticleAfterFetchContentObject'][] = 'ApprovedRevsHooks::showBlankIfUnapproved2';
$wgHooks['DisplayOldSubtitle'][] = 'ApprovedRevsHooks::setSubtitle';
// it's 'SkinTemplateNavigation' for the Vector skin, 'SkinTemplateTabs' for
// most other skins
$wgHooks['SkinTemplateTabs'][] = 'ApprovedRevsHooks::changeEditLink';
$wgHooks['SkinTemplateNavigation'][] = 'ApprovedRevsHooks::changeEditLinkVector';
$wgHooks['PageHistoryBeforeList'][] = 'ApprovedRevsHooks::storeApprovedRevisionForHistoryPage';
$wgHooks['PageHistoryLineEnding'][] = 'ApprovedRevsHooks::addApprovalLink';
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::setAsApproved';
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::unsetAsApproved';
$wgHooks['BeforeParserFetchTemplateAndtitle'][] = 'ApprovedRevsHooks::setTranscludedPageRev';
$wgHooks['ArticleDeleteComplete'][] = 'ApprovedRevsHooks::deleteRevisionApproval';
$wgHooks['MagicWordwgVariableIDs'][] = 'ApprovedRevsHooks::addMagicWordVariableIDs';
$wgHooks['ParserBeforeTidy'][] = 'ApprovedRevsHooks::handleMagicWords';
$wgHooks['AdminLinks'][] = 'ApprovedRevsHooks::addToAdminLinks';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'ApprovedRevsHooks::describeDBSchema';
$wgHooks['EditPage::showEditForm:initial'][] = 'ApprovedRevsHooks::addWarningToEditPage';
$wgHooks['sfHTMLBeforeForm'][] = 'ApprovedRevsHooks::addWarningToSFForm';
$wgHooks['ArticleViewHeader'][] = 'ApprovedRevsHooks::setArticleHeader';
$wgHooks['ArticleViewHeader'][] = 'ApprovedRevsHooks::displayNotApprovedHeader';

// Approved File Revisions
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::setFileAsApproved';
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::unsetFileAsApproved';
$wgHooks['ImagePageFileHistoryLine'][] = 'ApprovedRevsHooks::onImagePageFileHistoryLine';
$wgHooks['BeforeParserFetchFileAndTitle'][] = 'ApprovedRevsHooks::modifyFileLinks';
$wgHooks['ImagePageFindFile'][] = 'ApprovedRevsHooks::onImagePageFindFile';
$wgHooks['FileDeleteComplete'][] = 'ApprovedRevsHooks::onFileDeleteComplete';


// logging
$wgLogTypes['approval'] = 'approval';
$wgLogNames['approval'] = 'approvedrevs-logname';
$wgLogHeaders['approval'] = 'approvedrevs-logdesc';
$wgLogActions['approval/approve'] = 'approvedrevs-approveaction';
$wgLogActions['approval/unapprove'] = 'approvedrevs-unapproveaction';

// user rights
$wgAvailableRights[] = 'approverevisions'; // jamesmontalvo3: do we remove this or leave it behind even though it's not being used anymore?
$wgGroupPermissions['sysop']['approverevisions'] = true; // jamesmontalvo3: do we remove this or leave it behind even though it's not being used anymore?
$wgAvailableRights[] = 'viewlinktolatest';
$wgGroupPermissions['*']['viewlinktolatest'] = true;

// page properties
$wgPageProps['approvedrevs'] = 'Whether or not the page is approvable';

// ResourceLoader modules
$wgResourceModules['ext.ApprovedRevs'] = array(
	'styles' => 'ApprovedRevs.css',
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'ApprovedRevs'
);
