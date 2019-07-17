<?php
/* ------------------------------------------------------------------------- */

// first slice
if (rexx::isFrontend() && rexx::isFirstSliceOfSameType('REX_SLICE_ID')) {
	echo '<ul>';
}

/* ------------------------------------------------------------------------- */

// current slice
$listItem = 'REX_VALUE[1]';

echo '<li>' . $listItem . '</li>';

/* ------------------------------------------------------------------------- */

// last slice
if (rexx::isFrontend() && rexx::isLastSliceOfSameType('REX_SLICE_ID')) {
	echo '</ul>';
}

/* ------------------------------------------------------------------------- */
?>

