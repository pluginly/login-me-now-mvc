import apiFetch from "@wordpress/api-fetch";

const setInitialState = (store) => {
  apiFetch({
    path: "/login-me-now/admin/settings/?" + Math.random()
  }).then((data) => {
    const initialState = {
      settingsSavedNotification: "",
      settingsNotSavedNotification: "",
      magicLinkPopup: "",
      initialStateSetFlag: true,
      activeSettingsNavigationTab: "global-settings",
      activeSocialLoginNavigationTab: "google",

      lmnProLic: data.lmn_pro_lic,

      enableLogs: data.logs,
      logsExpiration: data.logs_expiration,

      enableUserSwitching: data.user_switching,

      enableGoogleLogin: data.google_login,
      enableGoogleClientID: data.google_client_id,
      enableGoogleClientSecret: data.google_client_secret,
      enableGoogleNativeLogin: data.google_native_login,
      enableGoogleUpdateExistingUserData: data.google_update_existing_user_data,
      enableGoogleUpdateExistingUserAvatar: data.google_pro_user_avatar,
      enableGoogleCancelOnTapOutside: data.google_cancel_on_tap_outside,
      selectGoogleSelectedLocation: data.google_onetap_display_location,
      selectGoogleProSelectedPages: data.google_pro_selected_pages,
      enableGoogleOneTap: data.google_onetap,

      enableGoogleLoginSelectLocation:
        data.google_login_select_location || true,
      selectGoogleProDefaultUserRole: data.google_pro_default_user_role,
      inputGoogleProRedirectUrl: data.google_pro_redirect_url,

      getUserRoles: data.get_user_roles,
      getPages: data.get_pages,

      blocksStatuses: data.pro_addons,

      dmTemporaryLogin: data.temporary_login,
      dmAdvanceShare: data.dm_advance_share,
      dmSocialLogin: data.social_login,
      dmExpressLoginWC: data.dm_express_login_wc,
      dmExpressLoginEDD: data.dm_express_login_edd,
      dmExpressLoginEmail: data.dm_express_login_email,
      dmOTPLogin: data.dm_otp_login,
      dmBrowserExtension: data.browser_extension,
      dmUserSwitching: data.user_switching,
      dmActivityLog: data.activity_logs,
      dmConditionalLogin: data.dm_conditional_login,
      
      lfEnableSignInGoogle: data.enable_sign_in_google,
      lfEnableSignInFacebook: data.enable_sign_in_facebook,
      lfEnableSignInTwitter: data.enable_sign_in_twitter,
      loginLayout: data.login_layout,
      loginButtonStyle: data.login_button_style,

      enableFacebookAppID: data.facebook_app_id,
      enableFacebookAppSecret: data.facebook_app_secret,
      enableFacebookLogin: data.facebook_login,
      enableFacebookNativeLogin: data.facebook_native_login,
      selectFacebookProDefaultUserRole: data.facebook_pro_default_user_role,
      enableFacebookUpdateExistingUserData: data.facebook_update_existing_user_data,
      enableFacebookUpdateExistingUserAvatar: data.facebook_pro_user_avatar,
      inputFacebookProRedirectUrl: data.facebook_pro_redirect_url,
    };

    store.dispatch({ type: "UPDATE_INITIAL_STATE", payload: initialState });
  });
};

export default setInitialState;
