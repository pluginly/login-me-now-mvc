import { __ } from '@wordpress/i18n';
import React from "react";

const ToolTip = () => {
	return (
		<div className="absolute right-2.5 -top-[1.75rem] opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-opacity duration-300 ease-in-out">
			<div
				id="tooltip-top"
				role="tooltip"
				className="inline-block z-10 px-2.5 py-1.5 text-[0.75rem] leading-[1rem] text-white bg-slate-800 rounded-sm shadow-sm opacity-1 tooltip"
			>
				{ __( 'This feature only available on Login Me Now Pro.', 'login-me-now' ) }
			</div>
			<div
				className="ml-auto w-2 h-2 flex -mt-1 rotate-45 bg-slate-800 mr-4 overflow-hidden"
			></div>
		</div>
	);
};

export default ToolTip;
