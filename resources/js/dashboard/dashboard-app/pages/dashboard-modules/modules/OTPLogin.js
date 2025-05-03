import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function OTPLogin({ colorChange, proItem, isAvailable }) {
  const isProAvailable = lmn_admin.pro_available ? true : false;
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const enableDmOTPLogin = useSelector((state) => state.dmOTPLogin);

  const handleDmOTPLogin = () => {
    let assetStatus;
    if (enableDmOTPLogin === false || enableDmOTPLogin === undefined) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_OTP_LOGIN",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "dm_otp_login");
    formData.append("value", assetStatus);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: "POST",
      body: formData,
    }).then(() => {
      dispatch({
        type: "UPDATE_SETTINGS_SAVED_NOTIFICATION",
        payload: __("Successfully saved!", "login-me-now"),
      });
    });
  };

  return (
    <div className={`mb-8 mx-4 flex`}>
      <div class={`relative rounded-[8px] border border-[#cacaca] flex flex-col justify-between} bg-[#F8FAFC]`}>
        <div className={`px-8 pt-16 pb-10 text-center responsive-box`}>
          <div className="bg-[#FFFFFF] border-[1px] border-[#DFDFDF] inline-block py-2.5 px-3 rounded-[8px] mb-4">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="30"
              height="30"
              viewBox="0 0 30 30"
              fill="none"
            >
              <path
                d="M25 7.5H5C3.61929 7.5 2.5 8.61929 2.5 10V20C2.5 21.3807 3.61929 22.5 5 22.5H25C26.3807 22.5 27.5 21.3807 27.5 20V10C27.5 8.61929 26.3807 7.5 25 7.5Z"
                stroke="#023A2E"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M15 15H15.0125M21.25 15H21.2625M8.75 15H8.7625"
                stroke="#023A2E"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5 flex justify-center items-center">
            OTP Login
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            Simplify users login with OTP authentication. Forget password
            hassles, a quick OTP sent via email , mobile or WhatsApp grants easy
            login to account.
          </p>
        </div>
        <div
          className={`bg-[#F0F2F4] py-3 rounded-b-[8px] flex ${
            isAvailable === true ? "justify-between" : "justify-center"
          } items-center px-4 border-t-[1px] border-t-[#cacaca] border-b-[#cacaca] ${
            hover && "invisible"
          }`}
        >
          {isAvailable === true ? (
            <>
              <button
                type="button"
                className={`bg-[#F8FAFC] border border-[#cacaca]  text-[#6B6D71] px-2 py-1 text-[14px] rounded-[8px] hover:border-[#0DA071]  hover:text-[#0DA071]`}
              >
                Settings
              </button>

              <Switch
                onChange={handleDmOTPLogin}
                className={classNames(
                  isProAvailable && enableDmOTPLogin
                    ? "bg-lmn"
                    : "bg-slate-200",
                  "group relative inline-flex h-[8px] w-[32px] flex-shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-lmn focus:ring-offset-2"
                )}
              >
                <span
                  aria-hidden="true"
                  className="pointer-events-none absolute h-full w-full rounded-md bg-white"
                />
                <span
                  aria-hidden="true"
                  className={classNames(
                    isProAvailable && enableDmOTPLogin
                      ? "bg-lmn"
                      : "bg-gray-200",
                    "pointer-events-none absolute mx-auto h-[16px] w-[32px] rounded-full transition-colors duration-200 ease-in-out"
                  )}
                />
                <span
                  aria-hidden="true"
                  className={classNames(
                    isProAvailable && enableDmOTPLogin
                      ? "translate-x-5"
                      : "translate-x-0",
                    "toggle-bubble pointer-events-none absolute left-0 inline-block h-[16px] w-[16px] transform rounded-full border border-gray-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out"
                  )}
                />
              </Switch>
            </>
          ) : (
            <button
              type="button"
              className={`bg-[#F8FAFC] cursor-auto border ${
                colorChange === true
                  ? "border-[#0DA071]  text-[#0DA071]"
                  : "border-[#cacaca]  text-[#023A2E]"
              } px-6 py-1 text-[14px] rounded-[8px]`}
            >
              Coming soon
            </button>
          )}
        </div>
        
      </div>
    </div>
  );
}

export default OTPLogin;
