import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function UserSwitching({ colorChange, proItem }) {
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmUserSwitching = useSelector(
    (state) => state.enableUserSwitching
  );

  const handleDmUserSwitching = () => {
    let assetStatus;
    if (
      enableDmUserSwitching === false ||
      enableDmUserSwitching === undefined
    ) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "UPDATE_ENABLE_USER_SWITCHING",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "user_switching");
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
        <div className={`px-8 pt-16 pb-10 text-center ${hover && "invisible"}`}>
          <div
            className="bg-[#FFFFFF] border-[1px] border-[#DFDFDF] inline-block py-2.5 px-3 rounded-[8px] mb-4"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="26"
              viewBox="0 0 24 26"
              fill="none"
            >
              <path
                d="M19.2364 7.81445C19.2364 3.80078 15.9844 0.548828 11.9708 0.548828C7.95709 0.548828 4.70514 3.80078 4.70514 7.81445C4.70514 10.2402 5.89459 12.3906 7.72272 13.709C7.711 13.7148 7.70221 13.7178 7.69635 13.7207C6.38678 14.2744 5.21197 15.0684 4.20123 16.082C3.19049 17.0928 2.39947 18.2705 1.84576 19.583C1.30377 20.8662 1.01373 22.2285 0.984435 23.6289C0.981505 23.7607 1.08697 23.8691 1.21881 23.8691H2.97369C3.09967 23.8691 3.20514 23.7666 3.20807 23.6406C3.26666 21.3789 4.17193 19.2607 5.77447 17.6553C7.42975 15.9941 9.62701 15.0801 11.9708 15.0801C15.9844 15.0801 19.2364 11.8281 19.2364 7.81445ZM11.9708 12.8535C9.18756 12.8535 6.9317 10.5977 6.9317 7.81445C6.9317 5.03125 9.18756 2.77539 11.9708 2.77539C14.754 2.77539 17.0098 5.03125 17.0098 7.81445C17.0098 10.5977 14.754 12.8535 11.9708 12.8535ZM15.0469 19.3281H22.7813C22.9102 19.3281 23.0157 19.2227 23.0157 19.0938V17.4531C23.0157 17.3242 22.9102 17.2188 22.7813 17.2188H17.6104L18.9932 15.458C19.0254 15.417 19.043 15.3643 19.043 15.3145C19.043 15.1855 18.9376 15.0801 18.8087 15.0801H16.6817C16.5381 15.0801 16.4034 15.1475 16.3126 15.2588L14.3057 17.8105C14.1768 17.9746 14.1065 18.1797 14.1065 18.3906C14.1094 18.9092 14.5284 19.3281 15.0469 19.3281ZM22.0782 21.2031H14.3438C14.2149 21.2031 14.1094 21.3086 14.1094 21.4375V23.0781C14.1094 23.207 14.2149 23.3125 14.3438 23.3125H19.5147L18.1319 25.0732C18.0997 25.1143 18.0821 25.167 18.0821 25.2168C18.0821 25.3457 18.1876 25.4512 18.3165 25.4512H20.4434C20.587 25.4512 20.7217 25.3838 20.8126 25.2725L22.8194 22.7207C22.9483 22.5566 23.0186 22.3516 23.0186 22.1406C23.0157 21.6221 22.5967 21.2031 22.0782 21.2031Z"
                fill="#023A2E"
              />
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5">
            User Switching
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            Easily switch between user accounts. Instant & in one-click!
          </p>
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

export default UserSwitching;
