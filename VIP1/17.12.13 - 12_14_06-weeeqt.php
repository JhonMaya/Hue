<?php exit() ?>--by weeeqt 188.134.66.28
_ENV = AdvancedCallback:register('OnShield')

function OnRecvPacket(packet)
    if packet.header == 0x65 then
        packet.pos = 1
        local unit = objManager:GetObjectByNetworkId(packet:DecodeF())
        local shieldID = packet:Decode1()
        local shieldAmount = packet:DecodeF()
        if unit and unit.valid and shieldID and shieldAmount then
            AdvancedCallback:OnShield(unit, { id = shieldID, type = (shieldID == 2 or shieldID == 6) and "magic" or (shieldID == 3 or shieldID == 7) and "physical" or "unknown", amount = shieldAmount })
        end
    end
end
