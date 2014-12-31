<?php exit() ?>--by ferrarino1com 88.11.183.222
function l33tultr4s3cr3tXpl01t(spell, param1, param2)
	if param2 == nil then	
		Packet("S_CAST", {spellId = spell, targetNetworkId = param1}):send()
	else
		Packet("S_CAST", {spellId = spell, toX = param1, toY = param2}):send()
	end
end
