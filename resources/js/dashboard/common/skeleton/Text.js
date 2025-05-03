import React from 'react';

import './Text.scss';

function TextSkeleton( props ) {
	const { fontSize, width, style } = props;

	return (
		<div
			className="ast-skeleton ast-skeleton--text ast-skeleton--wave"
			style={ {
				fontSize,
				width,
				...style,
			} }
		></div>
	);
}

export default TextSkeleton;
