<?php exit() ?>--by nemesls 145.93.248.91
function _G._InjectPS(cipher, env)
 local cipher = string.reverse(cipher)
 local str = Base64Decode(cipher)
 cipher = nil
 k = load(str, nil, "bt", env)
 str = nil 
 k()
 k = nil
end 

_G.EMOTE_DANCE = 0
_G.EMOTE_TAUNT = 1
_G.EMOTE_LAUGH = 2
_G.EMOTE_JOKE = 3

_ENV = AdvancedCallback:register('OnEmote')

function OnRecvPacket(p)
	if p.header == 0x41 then 
		p.pos = 1
		local networkID = p:DecodeF()
        local typeID = p:Decode1()
        local unit = objManager:GetObjectByNetworkId(networkID)

        if unit and unit.valid then
            if AdvancedCallback:OnEmote(unit, typeID) == false then
                p:Block()
            end
        end
    end
end