import React, { useState, useEffect } from "react";
import { CopyToClipboard } from "react-copy-to-clipboard";
import apiFetch from "@wordpress/api-fetch";
import { Tooltip } from "antd";

// Global cache for token links
const loginLinkCache = new Map();

function MyCopyToClipboard({ umeta_id }) {
  const [clipboardState, setClipboardState] = useState(false);
  const [tokenLink, setTokenLink] = useState("Loading...");

  useEffect(() => {
    if (!umeta_id) return;

    // Use cached link if available
    if (loginLinkCache.has(umeta_id)) {
      setTokenLink(loginLinkCache.get(umeta_id));
      return;
    }

    // Otherwise fetch it
    const formData = new window.FormData();
    formData.append("umeta_id", umeta_id);

    apiFetch({
      url: lmn_admin.rest_args.root + "/temporary-login/get-link",
      method: "POST",
      body: formData,
    })
      .then((data) => {
        if (data.success) {
          loginLinkCache.set(umeta_id, data.link); // cache it
          setTokenLink(data.link);
        }
      })
      .catch((error) => {
        console.error("Error fetching login link:", error);
        setTokenLink("Error");
      });
  }, [umeta_id]);

  const handleCopyClick = () => {
    setClipboardState(true);
    setTimeout(() => {
      setClipboardState(false);
    }, 2000);
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
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
            />
          </svg>
        ) : (
          <Tooltip title="Copy">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              strokeWidth="2"
              stroke="currentColor"
              aria-hidden="true"
              className="h-5 w-5"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
              />
            </svg>
          </Tooltip>
        )}
      </button>
    </CopyToClipboard>
  );
}

export default MyCopyToClipboard;