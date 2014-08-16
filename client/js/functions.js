function parseText( text )
{
	text = text.replace( /"/g, '&quot;'); // escapa todas las comillas de la cadena
	var length = text.length
	var substr = '';
	var str = '';
	sw = false;

	for( var i = 0; i < length; i++ )
	{
		// Si no se esta analizando un hashtag
		if( !sw )
		{
			// Comprueba de que el caracter sea un # que indica el inicio de un hashtag
			if( text.charAt( i ) == '#' )
			{
				sw = true; // cambia el estado de sw para indicar que los siguientes caracteres pertenecen a un hashtag
				substr = "<span class='hashtag'>#"
			}
			else // Sino, solo agrega el caracter a la cadena resultante
			{
				str += text.charAt( i );
			}
		}
		else
		{
			// Si el caracter no es un espacio, una coma o un punto
			// quiere decir que es parte del hashtag
			if( text.charAt( i ) != ' ' &&
				text.charAt( i ) != ',' &&
				text.charAt( i ) != '.'
			)
			{
				substr += text.charAt( i );

				// Si es el ultimo caracter del string y es parte del hashtag
				if( i + 1 == length )
				{

					substr += '</span> '
					str += substr;
				} // end if
			}
			else
			{
				substr += '</span>' + text.charAt( i );
				str += substr;
				substr = '';
				sw = false;
			} // end if...else
			
		} // end if...esle
		
	} // end for

	return str;
} // end parseText