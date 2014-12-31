<?php exit() ?>--by vadash 109.188.126.74
function CastSpellT(spell, target)
	if target then
		Packet("S_CAST", {spellId = spell, targetNetworkId = target.networkID}):send()
	end
end
_G.CastSpellT = CastSpellT