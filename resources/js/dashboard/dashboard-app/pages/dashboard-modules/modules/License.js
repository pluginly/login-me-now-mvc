import React, { useState, useEffect } from "react";
import { __ } from "@wordpress/i18n";
import { useSelector, useDispatch } from "react-redux";
import apiFetch from "@wordpress/api-fetch";

export default function License() {
  const [license, setLicense] = useState("");
  const [isLicenseActivate, setIsLicenseActivate] = useState(true);
  const [renderDeactivation, setRenderDeactivation] = useState(false);
  const [test, setTest] = useState("")
  const dispatch = useDispatch();
  const lmnProLic = useSelector((state) => state.lmnProLic);

  useEffect(() => {
    setLicense(lmnProLic);
  
    if (lmnProLic === undefined || lmnProLic === "") {
      setIsLicenseActivate(false);
    } else if (lmnProLic !== "") {
      setIsLicenseActivate(true);
    }
  }, [lmnProLic]);
  
  useEffect(() => {
    if (renderDeactivation) {
      const timeoutId = setTimeout(() => {
        setIsLicenseActivate(false);
        setRenderDeactivation(false);
      }, 2000);

      return () => clearTimeout(timeoutId);
    }
  }, [renderDeactivation]);

  const updateLicense = () => {
    const formData = new window.FormData();

    formData.append("action", "login_me_now_pro_activate_license");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "lmn_pro_lic");
    formData.append("value", isLicenseActivate === true ? "" : (license === "" ? "empty string" : license));

    apiFetch({
      url: lmn_admin.ajax_url,
      method: "POST",
      body: formData,
    })
      .then((data) => {
        setTest(data);
        if (false === data.success) {
          dispatch({
            type: "UPDATE_SETTINGS_NOT_SAVED_NOTIFICATION",
            payload: data.data,
          });
        }
        else {
            dispatch({
            type: "UPDATE_SETTINGS_SAVED_NOTIFICATION",
            payload: __("Successfully saved!", "login-me-now"),
          });
          

          if ("" === data.data.license) {
            setRenderDeactivation(true);
          } else {
            setIsLicenseActivate(true);
          }
        }
      })
      .catch((error) => {
        console.error("Error updating license:", error);
        dispatch({
          type: "UPDATE_SETTINGS_NOT_SAVED_NOTIFICATION",
          payload: "An error occurred while updating the license.",
        });
      })
      .finally(() => {
        setLicense("");
      });
  };

  const handleLicense = (e) => {
    if (!isLicenseActivate) {
      setLicense(e.target.value);
    } else {
      setLicense("");
    }
  };

  return (
    <div className="gap-4 items-start lg:grid-cols-5 lg:gap-0 xl:gap-0 rounded-md bg-white overflow-hidden shadow-sm p-8 mt-8">
      <h1 className="p-0 flex-1 justify-right inline-flex text-xl leading-6 font-semibold text-slate-800 mb-4">
        License Key
      </h1>
      <div className="flex">
        <input
          onChange={handleLicense}
          className={`block w-[70%] h-[50px] !p-3 !border-slate-200`}
          type="text"
          required
          name="lmn_pro_lic"
          placeholder={`${
            isLicenseActivate ? "******************************" : "Enter your license"
          }`}
          value={isLicenseActivate  ? "" : license}
          disabled={isLicenseActivate}
        />
        <button
          className={`h-[50px] ! w-[100px] !border-slate-200 border ml-3 rounded-[4px]  font-semibold ${isLicenseActivate ? 'bg-[#de070f] text-white' : 'text-[#5cabd3]'} `}
          onClick={updateLicense}
        >
          {isLicenseActivate ? "Deactivate" : "Activate"}
        </button>
      </div>
      <p className="text-[16px] font-medium text-[#878787] mt-4">An active license key is needed to unlock all the pro features and receive automatic plugin updates.</p>
    </div>
  );
}
