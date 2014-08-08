<?php
/*
	Copyright (C) 2012 Vernon Systems Limited

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if ($css_class == "") {
	echo '<div class="ehive-objects-image-grid">';
} else {
	echo '<div class="ehive-objects-image-grid '.$css_class.'">';
}

if (!isset($eHiveApiErrorMessage)) {
	$itemInlineStyleEnabled = false;
	$imageInlineStyleEnabled = false;
	$itemInlineStyle = '';
	$imageInlineStyle = '';
	
	if (isset($columns) && $columns > 0) {
		$sectionWidth = 100.0/$columns;
		$sectionMargin = $sectionWidth/($columns*$columns);
		$sectionWidth = $sectionWidth - $sectionMargin;
	
		$itemInlineStyle .= "max-width: {$sectionWidth}%; width: {$sectionWidth}%; margin-right: {$sectionMargin}%; margin-bottom: {$sectionMargin}%; ";
		$itemInlineStyleEnabled = true;
	}
	
	if (isset($item_background_colour_enabled) && $item_background_colour_enabled == 'on') {
		$itemInlineStyle .= "background-color:{$item_background_colour}; "; 
		$itemInlineStyleEnabled = true; 
	}
	if (isset($item_border_colour_enabled) && $item_border_colour_enabled == 'on') {
		$itemInlineStyle .= "border-style:solid; border-color:{$item_border_colour}; ";
		$itemInlineStyle .= "border-width:{$item_border_width}px; *margin:-{$item_border_width}px; "; 
		$itemInlineStyleEnabled = true; 
	}
	if (isset($image_background_colour_enabled) && $image_background_colour_enabled == 'on') {
		$imageInlineStyle .= "background:{$image_background_colour}; ";
		$imageInlineStyleEnabled = true; 
	}
	if (isset($image_padding_enabled) && $image_padding_enabled == 'on') {
		$imageInlineStyle .= "padding:{$image_padding}px; ";
		$imageInlineStyleEnabled = true;
	}
	if (isset($image_border_colour_enabled) && $image_border_colour_enabled == 'on') {
		$imageInlineStyle .= "border-style:solid; border-color:{$image_border_colour}; "; 
		$imageInlineStyle .= "border-width:{$image_border_width}px; "; 
		$imageInlineStyleEnabled = true; 
	}
		
	if($itemInlineStyleEnabled) {
		$itemInlineStyle = " style='$itemInlineStyle'";
	}
	if($imageInlineStyleEnabled) {
		$imageInlineStyle = " style='$imageInlineStyle'";
	}
	
	echo "<div class='ehive-view ehive-image-grid'>";
	
	foreach($objectRecordsCollection->objectRecords as $objectRecord){
					
		$imageMediaSet = $objectRecord->getMediaSetByIdentifier('image');
		if (isset($imageMediaSet)){
					
			$mediaRow = $imageMediaSet->mediaRows[0];
			$tinySquareImageMedia = $mediaRow->getMediaByIdentifier($imageSize);
			
			echo "<div class='ehive-item' $itemInlineStyle >";
				echo '<div class="ehive-item-image-wrap">';
					echo '<a class="ehive-image-link" href="'. $eHiveAccess->getObjectDetailsPageLink($objectRecord->objectRecordId) .'">';
						echo '<img class="ehive-image" src="'.$tinySquareImageMedia->getMediaAttribute('url').'" '.$imageInlineStyle.' height="'.$tinySquareImageMedia->getMediaAttribute('height').'" width="'.$tinySquareImageMedia->getMediaAttribute('width').'"/>';
					echo '</a>';
				echo '</div>';
				echo '<div class="ehive-item-metadata-wrap">';
				if ( $name_enabled ) {
					echo '<p class="ehive-field ehive-identifier-name">';
					$fieldSet = $objectRecord->getFieldSetByIdentifier('name');
					if(isset($fieldSet)) {
						$fieldRow = $fieldSet->fieldRows[0];
						if(isset($fieldRow)) {
							$nameTitle = $fieldRow->getFieldByIdentifier('name');
							echo '<a class="ehive-name-link" href="'. $eHiveAccess->getObjectDetailsPageLink($objectRecord->objectRecordId) .'">';
								echo $nameTitle->getFieldAttribute('value');
							echo '</a>';
						}
					}
					echo '</p>';
				}
				echo '</div>';
			echo '</div>';
		}
		if ($column == $columns ) {  // End of an image row.
			echo '</div>';
			$column = 0;
		}
	}
	if ($column > 0 && $column < $columns) {  // End of an image row where the number of columns does not divide evenly. A short row. 
		echo "</div>";
	}
	echo "</div>";
} else {
	echo "<p class='ehive-error-message ehive-objects-image-grid-error'>$eHiveApiErrorMessage</p>";
}
echo "</div>";
?>