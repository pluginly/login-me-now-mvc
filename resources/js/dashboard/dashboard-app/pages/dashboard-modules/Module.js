import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function Module({ colorChange, title, subtitle, proItem, none, data }) {
  const dispatch = useDispatch();
  const [isChecked, setIsChecked] = useState(true);
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmTemporaryLogin = useSelector((state) => state.dmTemporaryLogin);

  const test = useSelector((state) => state);

  const handleDmTemporaryLogin = () => {
    // setIsChecked(!enableDmTemporaryLogin);
    let assetStatus;
    if (
      enableDmTemporaryLogin === false ||
      enableDmTemporaryLogin === undefined
    ) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_TEMPORARY_LOGIN",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "temporary_login");
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
    <div className="mb-8 mx-4 flex cursor-pointer">
      <div
        class={`relative rounded-[8px] border border-[#9F9F9F] flex flex-col justify-between ${
          none && "hidden"
        } ${hover === true ? "bg-[#0da071b0]" : "bg-[#F8FAFC]"}`}
        onMouseEnter={proItem === true ? handleMouseEnter : null}
        onMouseLeave={proItem === true ? handleMouseLeave : null}
      >
        <div className={`px-8 pt-16 pb-10 ${hover && "invisible"}`}>
          <h1 className="text-[#000000] text-[16px] font-medium text-center mb-5">
            {title}
            {proItem && (
              <span className="bg-[#0DA071] text-[#ffffff] px-2 py-0.5 text-[8px] rounded-[4px] ml-1.5">
                Pro
              </span>
            )}
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center">{subtitle}</p>
        </div>
        <div
          className={`bg-[#F0F2F4] py-3 rounded-b-[8px] flex justify-between items-center px-4 border-t-[2px] border-b-[#9F9F9F] ${
            hover && "invisible"
          }`}
        >
          <button
            type="button"
            className={`bg-[#F8FAFC] border border-[#9F9F9F]  text-[#6B6D71] px-2 py-1 text-[14px] rounded-[8px] hover:border-[#0DA071]  hover:text-[#0DA071]`}
          >
            Settings
          </button>
          <Switch
            // defaultChecked={true}
            checked={true}
            onChange={handleDmTemporaryLogin}
            className={classNames(
              enableDmTemporaryLogin ? "bg-lmn" : "bg-slate-200",
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
                enableDmTemporaryLogin ? "bg-lmn" : "bg-gray-200",
                "pointer-events-none absolute mx-auto h-[16px] w-[32px] rounded-full transition-colors duration-200 ease-in-out"
              )}
            />
            <span
              aria-hidden="true"
              className={classNames(
                enableDmTemporaryLogin ? "translate-x-5" : "translate-x-0",
                "toggle-bubble pointer-events-none absolute left-0 inline-block h-[16px] w-[16px] transform rounded-full border border-gray-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out"
              )}
            />
          </Switch>
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

export default Module;
