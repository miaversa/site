#!/bin/bash

# $1 caminho da imagem
# $2 numero de iteracoes
# $3 tamanho da imagem padrao
function resize_media_forty_three {
	def=$3
	root=`dirname $1`
	filename=`basename $1`
	extension=".${filename##*.}"
	filename="${filename%.*}"
	filename_reduzido="${filename%-*}"

	for n in `seq 1 $2`;
	do
		cent="$n"00
		original=$1
		interdir="$root/tmp/"
		interfile="$root/tmp/$filename$extension"
		destino="$root/$filename_reduzido-$cent$extension"
		mkdir -p $interdir
		mogrify -path $interdir -filter Triangle -define filter:support=2 -thumbnail $cent -unsharp 0.25x0.25+8+0.065 -dither None -posterize 136 -quality 82 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip $original
		jpegtran -copy none -optimize $interfile > $destino
	done

	original=$1
	interdir="$root/tmp/"
	interfile="$root/tmp/$filename$extension"
	destino="$root/$filename_reduzido$extension"
	mkdir -p $interdir
	mogrify -path $interdir -filter Triangle -define filter:support=2 -thumbnail $def -unsharp 0.25x0.25+8+0.065 -dither None -posterize 136 -quality 82 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip $original
	jpegtran -copy none -optimize $interfile > $destino

	rm -rf $interdir
}

resize_media_forty_three "content/images/media/serrando-original.jpg" 4 400
resize_media_forty_three "content/images/media/recozendo-original.jpg" 9 400

############################################################################


composite -gravity center "content/images/magico4.png" \
"content/images/produtos/anel-b/anel-b-1-original.jpg" \
"content/images/produtos/anel-b/anel-b-1.jpg"

composite -gravity center "content/images/magico4.png" \
"content/images/produtos/anel-b/anel-b-2-original.jpg" \
"content/images/produtos/anel-b/anel-b-2.jpg"

composite -gravity center "content/images/magico4.png" \
"content/images/produtos/anel-b/anel-b-3-original.jpg" \
"content/images/produtos/anel-b/anel-b-3.jpg"
