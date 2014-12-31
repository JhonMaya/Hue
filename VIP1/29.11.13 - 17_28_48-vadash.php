<?php exit() ?>--by vadash 109.188.126.74
function CastSpellT(target)
	if target then
		Packet("S_CAST", {spellId = _E, targetNetworkId = target.networkID}):send()
	end
end