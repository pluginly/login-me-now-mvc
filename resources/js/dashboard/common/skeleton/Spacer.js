import React from 'react';

import './Spacer.scss';

function SpacerSkeleton( props ) {
	const { height, style } = props;

	return (
		<div
			className="ast-skeleton-base ast-skeleton--spacer"
			style={ {
				height,
				...style,
			} }
		></div>
	);
}

export default SpacerSkeleton;
