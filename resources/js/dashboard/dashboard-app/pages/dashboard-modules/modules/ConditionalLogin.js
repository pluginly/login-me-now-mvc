import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function ConditionalLogin({ colorChange, proItem, isAvailable }) {
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmConditionalLogin = useSelector(
    (state) => state.dmConditionalLogin
  );

  const handleDmConditionalLogin = () => {
    let assetStatus;
    if (
      enableDmConditionalLogin === false ||
      enableDmConditionalLogin === undefined
    ) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_CONDITIONAL_LOGIN",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "dm_conditional_login");
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
    <div className="mb-8 mx-4 flex">
      <div
        class={`relative rounded-[8px] border border-[#cacaca] flex flex-col justify-between ${
          hover === true ? "bg-[#0da071b0]" : "bg-[#F8FAFC]"
        }`}
        onMouseEnter={proItem === true ? handleMouseEnter : null}
        onMouseLeave={proItem === true ? handleMouseLeave : null}
      >
        <div className={`px-8 pt-16 pb-10 text-center responsive-box ${hover && "invisible"}`}>
          <div
            className="bg-[#FFFFFF] border-[1px] border-[#DFDFDF] inline-block py-2.5 px-3 rounded-[8px] mb-4"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="30"
              height="30"
              viewBox="0 0 30 30"
              fill="none"
            >
              <g clip-path="url(#clip0_1377_12)">
                <path
                  d="M10.7143 10.7151C13.3771 10.7151 15.5357 8.5565 15.5357 5.89369C15.5357 3.23089 13.3771 1.07227 10.7143 1.07227C8.05145 1.07227 5.89282 3.23089 5.89282 5.89369C5.89282 8.5565 8.05145 10.7151 10.7143 10.7151Z"
                  stroke="#023A2E"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  d="M21.9643 28.9286C25.8106 28.9286 28.9286 25.8106 28.9286 21.9643C28.9286 18.118 25.8106 15 21.9643 15C18.118 15 15 18.118 15 21.9643C15 25.8106 18.118 28.9286 21.9643 28.9286Z"
                  stroke="#023A2E"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  d="M17.0356 26.8931L26.8928 17.036M12.8571 14.1646C11.444 13.8383 9.97556 13.8358 8.56141 14.157C7.14725 14.4782 5.82401 15.115 4.6906 16.0197C3.55719 16.9243 2.64295 18.0735 2.01621 19.3813C1.38948 20.689 1.06647 22.1215 1.07134 23.5717V26.786H9.64277"
                  stroke="#023A2E"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </g>
              <defs>
                <clipPath id="clip0_1377_12">
                  <rect width="30" height="30" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5">
            Conditional Login
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            Provide limited access to the dashboard and admin bar options to
            specific role based users such as shop manager, editor, etc.
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
                onChange={handleDmConditionalLogin}
                className={classNames(
                  enableDmConditionalLogin ? "bg-lmn" : "bg-slate-200",
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
                    enableDmConditionalLogin ? "bg-lmn" : "bg-gray-200",
                    "pointer-events-none absolute mx-auto h-[16px] w-[32px] rounded-full transition-colors duration-200 ease-in-out"
                  )}
                />
                <span
                  aria-hidden="true"
                  className={classNames(
                    enableDmConditionalLogin
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
        <button
          className={`bg-white px-6 py-3 text-[18px] font-semibold text-[#0DA071] rounded-[8px] absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 ${
            !hover && "invisible"
          }`}
        >
          Buy Pro
        </button>
      </div>
    </div>
  );
}

export default ConditionalLogin;
