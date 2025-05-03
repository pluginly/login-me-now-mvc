import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import { Link } from "react-router-dom/cjs/react-router-dom.min";
import {Tooltip} from "antd";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function ActivityLog({ colorChange, proItem, isAvailable }) {
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmActivityLog = useSelector((state) => state.dmActivityLog);
  const handleDmActivityLog = () => {
    let assetStatus;
    if (enableDmActivityLog === false || enableDmActivityLog === undefined) {
      assetStatus = true;
    } else{
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_ACTIVITY_LOGS",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "activity_logs");
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
              height="26"
              viewBox="0 0 30 26"
              fill="none"
            >
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 1C0 0.734784 0.105357 0.48043 0.292893 0.292893C0.48043 0.105357 0.734784 0 1 0H5C5.26522 0 5.51957 0.105357 5.70711 0.292893C5.89464 0.48043 6 0.734784 6 1C6 1.26522 5.89464 1.51957 5.70711 1.70711C5.51957 1.89464 5.26522 2 5 2H1C0.734784 2 0.48043 1.89464 0.292893 1.70711C0.105357 1.51957 0 1.26522 0 1ZM8 1C8 0.734784 8.10536 0.48043 8.29289 0.292893C8.48043 0.105357 8.73478 0 9 0H29C29.2652 0 29.5196 0.105357 29.7071 0.292893C29.8946 0.48043 30 0.734784 30 1C30 1.26522 29.8946 1.51957 29.7071 1.70711C29.5196 1.89464 29.2652 2 29 2H9C8.73478 2 8.48043 1.89464 8.29289 1.70711C8.10536 1.51957 8 1.26522 8 1ZM8 7C8 6.73478 8.10536 6.48043 8.29289 6.29289C8.48043 6.10536 8.73478 6 9 6H23C23.2652 6 23.5196 6.10536 23.7071 6.29289C23.8946 6.48043 24 6.73478 24 7C24 7.26522 23.8946 7.51957 23.7071 7.70711C23.5196 7.89464 23.2652 8 23 8H9C8.73478 8 8.48043 7.89464 8.29289 7.70711C8.10536 7.51957 8 7.26522 8 7ZM0 13C0 12.7348 0.105357 12.4804 0.292893 12.2929C0.48043 12.1054 0.734784 12 1 12H5C5.26522 12 5.51957 12.1054 5.70711 12.2929C5.89464 12.4804 6 12.7348 6 13C6 13.2652 5.89464 13.5196 5.70711 13.7071C5.51957 13.8946 5.26522 14 5 14H1C0.734784 14 0.48043 13.8946 0.292893 13.7071C0.105357 13.5196 0 13.2652 0 13ZM8 13C8 12.7348 8.10536 12.4804 8.29289 12.2929C8.48043 12.1054 8.73478 12 9 12H29C29.2652 12 29.5196 12.1054 29.7071 12.2929C29.8946 12.4804 30 12.7348 30 13C30 13.2652 29.8946 13.5196 29.7071 13.7071C29.5196 13.8946 29.2652 14 29 14H9C8.73478 14 8.48043 13.8946 8.29289 13.7071C8.10536 13.5196 8 13.2652 8 13ZM8 19C8 18.7348 8.10536 18.4804 8.29289 18.2929C8.48043 18.1054 8.73478 18 9 18H23C23.2652 18 23.5196 18.1054 23.7071 18.2929C23.8946 18.4804 24 18.7348 24 19C24 19.2652 23.8946 19.5196 23.7071 19.7071C23.5196 19.8946 23.2652 20 23 20H9C8.73478 20 8.48043 19.8946 8.29289 19.7071C8.10536 19.5196 8 19.2652 8 19ZM0 25C0 24.7348 0.105357 24.4804 0.292893 24.2929C0.48043 24.1054 0.734784 24 1 24H5C5.26522 24 5.51957 24.1054 5.70711 24.2929C5.89464 24.4804 6 24.7348 6 25C6 25.2652 5.89464 25.5196 5.70711 25.7071C5.51957 25.8946 5.26522 26 5 26H1C0.734784 26 0.48043 25.8946 0.292893 25.7071C0.105357 25.5196 0 25.2652 0 25ZM8 25C8 24.7348 8.10536 24.4804 8.29289 24.2929C8.48043 24.1054 8.73478 24 9 24H29C29.2652 24 29.5196 24.1054 29.7071 24.2929C29.8946 24.4804 30 24.7348 30 25C30 25.2652 29.8946 25.5196 29.7071 25.7071C29.5196 25.8946 29.2652 26 29 26H9C8.73478 26 8.48043 25.8946 8.29289 25.7071C8.10536 25.5196 8 25.2652 8 25Z"
                fill="#023A2E"
              />
            </svg>
          </div>
          <h1 className="text-[#000000] text-[16px] font-medium text-center mb-5">
            <a className="border-b-[#2271b1] pb-1 border-b-[2px] border-dashed" href="https://wordpress.org/plugins/simple-history/" target="_blank">Simple History</a> Integration
            {proItem && (
              <span className="bg-[#0DA071] text-[#ffffff] px-2 py-0.5 text-[8px] rounded-[4px] ml-1.5">
                Pro
              </span>
            )}
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            Keep an activity log of everything that occurs when a user logs in
            to the dashboard using the tokenized login link.
          </p>
        </div>
      </div>
    </div>
  );
}

export default ActivityLog;
