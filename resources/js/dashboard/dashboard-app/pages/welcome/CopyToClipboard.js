import React, { useState, useEffect } from "react";
import { CopyToClipboard } from "react-copy-to-clipboard";
import apiFetch from "@wordpress/api-fetch";
import { Tooltip } from "antd";

function MyCopyToClipboard({ umeta_id }) {
  const [clipboardState, setClipboardState] = useState(false);
  const [tokenLink, setTokenLink] = useState("Nothing to copy");

  useEffect(() => {
    fetchLoginLink(umeta_id);
  }, [umeta_id]);

  const handleCopyClick = () => {
    setClipboardState(true);

    setTimeout(() => {
      setClipboardState(false);
    }, 2000);
  };

  const fetchLoginLink = (umeta_id) => {
    const formData = new window.FormData();
    formData.append("action", "login_me_now_login_link_get_link");
    formData.append("security", lmn_admin.generate_token_nonce);
    formData.append("umeta_id", umeta_id);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: "POST",
      body: formData,
    })
      .then((data) => {
        if (data.success) {
          setTokenLink(data.data);
        }
      })
      .catch((error) => {
        console.log(error);
      });
  };

  return (
    <CopyToClipboard text={tokenLink} onCopy={handleCopyClick}>
      <button>
        {clipboardState ? (
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth="2"
            stroke="currentColor"
            aria-hidden="true"
            className="h-5 w-5 text-[#50d71e]"
          >
              <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
            ></path>
          </svg>
        ) : (
          <Tooltip title="Copy"><svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          strokeWidth="2"
          stroke="currentColor"
          aria-hidden="true"
          className="h-5 w-5"
        >
            <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
          ></path>
        </svg></Tooltip>
        )}
      </button>
    </CopyToClipboard>
  );
}

export default MyCopyToClipboard;
