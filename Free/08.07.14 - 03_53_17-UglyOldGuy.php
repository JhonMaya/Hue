<?php exit() ?>--by UglyOldGuy 86.127.152.145
_G.rLoader = function(env, data)
	function FromHex(str)
		return(
			string.gsub(str, "(%x%x)[ ]?",
				function (c)
					return string.char(tonumber(c, 16))
				end
			)
		)
	end

	assert(load(Base64Decode(FromHex(data)), nil, "bt", env))()
end